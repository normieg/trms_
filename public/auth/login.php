<?php
require_once __DIR__ . '/../../app/dbh/db.inc.php';
require_once __DIR__ . '/../assets/tailwind-classes/classes.php';

// Start a session for CSRF token handling
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Mint a CSRF token if one doesn't exist
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>

<body class="<?php echo $loginStyle['container']; ?>">
    <div class="<?php echo $loginStyle['card']; ?>">
        <div class="<?php echo $loginStyle['header']; ?>">
            <img src="../assets/images/filstarlogo.png" alt="Logo" class="<?php echo $loginStyle['logo']; ?>">
            <div>
                <h1 class="<?php echo $loginStyle['logoTitle']; ?>">FDC LOGISTICS</h1>
                <p class="<?php echo $loginStyle['logoSubtitle']; ?>">Beyond Distribution</p>
            </div>
        </div>

        <h2 class="<?php echo $loginStyle['heading']; ?>">Log in to your account</h2>

        <form action="/trms/app/auth-handler/login-handler.php" method="POST" class="<?php echo $loginStyle['form']; ?>">
            <!-- Hidden CSRF token field -->
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">

            <!-- Username input -->
            <input type="text" name="username" placeholder="Enter your username"
                class="<?php echo $loginStyle['form_input']; ?>" required>

            <!-- Password input -->
            <input type="password" name="password" placeholder="Enter your password"
                class="<?php echo $loginStyle['form_input']; ?>" required>

            <!-- Submit button -->
            <button type="submit" class="<?php echo $loginStyle['submit_button']; ?>">Login</button>
        </form>

        <div class="text-center mt-4">
            <a href="#" class="<?php echo $loginStyle['forgot_link']; ?>">Forgot password?</a>
        </div>
    </div>
</body>

</html>