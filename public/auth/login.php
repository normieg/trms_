<?php
require_once __DIR__ . '/../../app/dbh/db.inc.php';
require_once __DIR__ . '/../assets/tailwind-classes/classes.php';
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

        <h2 class="<?php echo $loginStyle['heading']; ?>">Sign in to your account</h2>

        <form action="../../app/auth-handler/login-handler.php" method="POST" class="<?php echo $loginStyle['form']; ?>">
            <input type="text" name="username" placeholder="System ID" class="<?php echo $loginStyle['form_input']; ?>" required>
            <input type="password" name="password" placeholder="Password" class="<?php echo $loginStyle['form_input']; ?>" required>
            <button type="submit" class="<?php echo $loginStyle['submit_button']; ?>">Login</button>
        </form>

        <div class="text-center mt-4">
            <a href="#" class="<?php echo $loginStyle['forgot_link']; ?>">Forgot password?</a>
        </div>
    </div>
</body>
</html>