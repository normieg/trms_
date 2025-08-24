<?php

declare(strict_types=1);

// Dummy hash used to make password verification constant-time
// even when the username doesn't exist (prevents timing leaks)
const DUMMY_HASH = '$2y$12$wBl7bgeDLePhDZgALiz3kemDGB.uzU.M2uLgKgrvWJC7nYZhjULCy';

// Detect HTTPS properly (direct or behind proxy)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

// Harden session configuration
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

// Secure session cookies
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once __DIR__ . '/../../app/dbh/db.inc.php';

// Only allow POST requests to this endpoint
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Validate CSRF token to prevent login CSRF attacks
if (
    !isset($_POST['csrf']) || !isset($_SESSION['csrf']) ||
    !hash_equals($_SESSION['csrf'], $_POST['csrf'])
) {
    http_response_code(403);
    exit('Forbidden');
}

// Extract and sanitize input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Reject blank submissions early
if ($username === '' || $password === '') {
    header('Location: /public/auth/login.php?error=invalid');
    exit();
}

// Rate-limit: track failed attempts per IP within 10 minutes
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$now = time();
if (!isset($_SESSION['login_failures'])) {
    $_SESSION['login_failures'] = [];
}
$attempts = $_SESSION['login_failures'][$ip] ?? [];
$attempts = array_filter($attempts, fn($ts) => $ts > $now - 600);
if (count($attempts) >= 20) {
    http_response_code(429);
    exit('Too Many Requests');
}
$_SESSION['login_failures'][$ip] = $attempts;

// Lookup user by username (safe with prepared statement)
$stmt = $conn->prepare('SELECT id, username, full_name, password, role, status FROM users WHERE username = ? LIMIT 1');

if (!$stmt) {
    header('Location: /public/auth/login.php?error=server');
    exit();
}

$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Always run password_verify: prevents user enumeration via timing
$hashToCheck = $user['password'] ?? DUMMY_HASH;
$verified = password_verify($password, $hashToCheck);

// Reject if no user, password mismatch, or inactive status
if (!$user || !$verified || $user['status'] !== 'active') {
    $_SESSION['login_failures'][$ip][] = $now;
    usleep(random_int(100000, 300000)); // add jitter against brute force timing
    header('Location: /public/auth/login.php?error=invalid');
    exit();
}

// Upgrade legacy hashes to current algorithm
if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $updateStmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
    if ($updateStmt) {
        $updateStmt->bind_param('si', $newHash, $user['id']);
        $updateStmt->execute();
        $updateStmt->close();
    }
}

// Reset session to prevent fixation attacks
session_regenerate_id(true);

// Clear recorded failures on successful login
unset($_SESSION['login_failures'][$ip]);

// Store user identity and role in the session
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role'] = $user['role'];

// Role-based redirect mapping (strict allowlist)
$roleRedirects = [
    'admin'    => '/trms/public/admin/admin-dashboard.php',
    'staff'    => '/trms/public/staff/home.php',
    'mechanic' => '/trms/public/mechanic/workbench.php',
];

// Support optional ?next param but only if in allowlist
$next = $_POST['next'] ?? $_GET['next'] ?? '';
$redirect = $roleRedirects[$_SESSION['role']] ?? '/';
if ($next && in_array($next, $roleRedirects, true)) {
    $redirect = $next;
}

// Close DB connection
$conn->close();

// Send user to their landing page
header('Location: ' . $redirect);
exit();
