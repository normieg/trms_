<?php
declare(strict_types=1);

const DUMMY_HASH = '$2y$12$wBl7bgeDLePhDZgALiz3kemDGB.uzU.M2uLgKgrvWJC7nYZhjULCy';

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

// Ensure secure session cookies
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once __DIR__ . '/../../app/dbh/db.inc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) ||
    !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    http_response_code(403);
    exit('Forbidden');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: /public/auth/login.php?error=invalid');
    exit();
}

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

$hashToCheck = $user['password'] ?? DUMMY_HASH;
$verified = password_verify($password, $hashToCheck);

if (!$user || !$verified || $user['status'] !== 'active') {
    $_SESSION['login_failures'][$ip][] = $now;
    // Mitigate timing attacks and brute force by adding random delay
    usleep(random_int(100000, 300000));
    header('Location: /public/auth/login.php?error=invalid');
    exit();
}

// Rehash password if algorithm has changed
if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $updateStmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
    if ($updateStmt) {
        $updateStmt->bind_param('si', $newHash, $user['id']);
        $updateStmt->execute();
        $updateStmt->close();
    }
}

session_regenerate_id(true);
unset($_SESSION['login_failures'][$ip]);
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role'] = $user['role'];

// Close connection explicitly
$conn->close();

header('Location: /');
exit();
