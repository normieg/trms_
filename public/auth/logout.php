<?php
// Secure logout endpoint
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Ensure a CSRF token exists
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// Helper to redirect to login (adjust path if your public document root differs)
function redirect_to_login($query = '')
{
    header('Location: /trms/public/auth/login.php' . ($query ? "?" . $query : ""));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    $posted = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'], $posted)) {
        // Invalid token — treat as bad request and send back to login with error
        redirect_to_login('error=csrf');
    }

    // Clear session data
    $_SESSION = [];

    // Delete session cookie if present
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // Destroy the session
    session_destroy();

    // Optionally regenerate a fresh session id when returning to the login page
    redirect_to_login('loggedout=1');
}

// GET — render a small confirmation page with a POST form (CSRF-protected)
?>
