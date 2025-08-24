<?php
// Add User Handler
// Validates input, checks CSRF, ensures username uniqueness, hashes password, and inserts into users table.

declare(strict_types=1);

// Start session (for CSRF)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /trms/public/admin/staff-management/manage-staff.php');
    exit;
}

require_once __DIR__ . '/../dbh/db.inc.php';

// Helper: redirect back with query params
function redirect_with(array $params): void
{
    $base = '/trms/public/admin/staff-management/manage-staff.php';
    $query = http_build_query($params);
    header('Location: ' . $base . ($query ? ('?' . $query) : ''));
    exit;
}

// CSRF validation
$csrf = $_POST['csrf'] ?? '';
if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$csrf)) {
    redirect_with(['error' => 'Invalid request (CSRF).']);
}

// Extract and sanitize inputs
$full_name = trim((string)($_POST['full_name'] ?? ''));
$username  = trim((string)($_POST['username'] ?? ''));
$password  = (string)($_POST['password'] ?? '');
$confirm   = (string)($_POST['confirm_password'] ?? '');
$role      = trim((string)($_POST['role'] ?? 'staff'));   // default for this page
$status    = trim((string)($_POST['status'] ?? 'active'));

// Constrain role and status to allowed values
$allowed_roles = ['staff', 'mechanic', 'admin'];
if (!in_array($role, $allowed_roles, true)) {
    $role = 'staff';
}
// Only admins may assign the admin role
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
if (!$isAdmin && $role === 'admin') {
    $role = 'staff';
}

$allowed_status = ['active', 'inactive'];
if (!in_array($status, $allowed_status, true)) {
    $status = 'active';
}

// Validate
$errors = [];
if ($full_name === '' || mb_strlen($full_name) < 3) {
    $errors[] = 'Full name must be at least 3 characters.';
}
if ($username === '' || mb_strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters.';
}
if (mb_strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}
if ($password !== $confirm) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    redirect_with(['error' => implode(' ', $errors)]);
}

// Check username uniqueness
$stmt = $conn->prepare('SELECT 1 FROM users WHERE username = ? LIMIT 1');
if (!$stmt) {
    redirect_with(['error' => 'Server error. Please try again later.']);
}
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    redirect_with(['error' => 'Username already in use.']);
}
$stmt->close();

// Insert user
$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $conn->prepare('INSERT INTO users (username, full_name, password, role, status) VALUES (?, ?, ?, ?, ?)');
if (!$insert) {
    redirect_with(['error' => 'Server error (insert). Please try again.']);
}
$insert->bind_param('sssss', $username, $full_name, $hash, $role, $status);
if (!$insert->execute()) {
    $insert->close();
    redirect_with(['error' => 'Failed to create user.']);
}
$insert->close();

redirect_with(['success' => 'staff_created']);
