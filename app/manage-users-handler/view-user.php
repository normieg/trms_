<?php
// View User Handler
// Returns user details as JSON for admin use.

declare(strict_types=1);

header('Content-Type: application/json');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Ensure the requester is an admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

require_once __DIR__ . '/../dbh/db.inc.php';

// Validate user ID
$userId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$userId || $userId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

// Fetch user using a prepared statement
$stmt = $conn->prepare('SELECT id, username, full_name, role, status FROM users WHERE id = ? LIMIT 1');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    exit;
}
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit;
}

echo json_encode(['user' => $user]);
