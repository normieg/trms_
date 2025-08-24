<?php
// Shared init for public includes: session, CSRF token, and public root calculation
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    // Use random_bytes when available
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// Compute public root (example: /trms/public). This makes included paths resilient
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$publicRoot = '/trms/public';
if (strpos($scriptName, '/public') !== false) {
    $publicRoot = substr($scriptName, 0, strpos($scriptName, '/public') + strlen('/public'));
}

// Small helper to echo the public root safely when needed
function public_root()
{
    global $publicRoot;
    return htmlspecialchars($publicRoot, ENT_QUOTES, 'UTF-8');
}
