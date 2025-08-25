<?php
// Delete User Handler
// Validates CSRF, ensures admin privileges, and deletes the user securely.

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /trms/public/admin/staff-management/manage-staff.php');
    exit;
}

// Helper redirect function
function redirect_with(array $params): void {
    $base = '/trms/public/admin/staff-management/manage-staff.php';
    $query = http_build_query($params);
    header('Location: ' . $base . ($query ? ('?' . $query) : ''));
    exit;
}

require_once __DIR__ . '/../dbh/db.inc.php';

// CSRF validation
$csrf = $_POST['csrf'] ?? '';
if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$csrf)) {
    redirect_with(['error' => 'Invalid request (CSRF).']);
}

// Ensure the requester is an admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    redirect_with(['error' => 'Insufficient permissions.']);
}

// Validate user ID
$userId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$userId || $userId <= 0) {
    redirect_with(['error' => 'Invalid user ID.']);
}

// Prevent admins from deleting their own account
if (($_SESSION['user_id'] ?? 0) === $userId) {
    redirect_with(['error' => 'You cannot delete your own account.']);
}

// Delete user securely using a prepared statement
$stmt = $conn->prepare('DELETE FROM users WHERE id = ? LIMIT 1');
if (!$stmt) {
    redirect_with(['error' => 'Server error (prepare).']);
}
$stmt->bind_param('i', $userId);
if (!$stmt->execute()) {
    $stmt->close();
    redirect_with(['error' => 'Failed to delete user.']);
}
$stmt->close();

redirect_with(['success' => 'user_deleted']);
