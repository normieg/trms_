<?php
require_once __DIR__ . '/../../app/dbh/db.inc.php';
require_once __DIR__ . '/../assets/tailwind-classes/classes.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>

<body class="<?php echo $loginStyle['container']; ?>">

    <div class="flex items-center justify-center w-full">
        <div class="<?php echo $loginStyle['form_container']; ?>">
            <form action="/../../app/auth-handler/login-handler.php" method="POST">
                <div class="<?php echo $loginStyle['logoWrapper']; ?>">
                    <!-- Logo on the left -->
                    <img src="../assets/images/filstarlogo.png" alt="Logo" class="<?php echo $loginStyle['logo']; ?> ">

                    <!-- Brand name and tagline on the right -->
                    <div class="text-right">
                        <span class="<?php echo $loginStyle['logoTitle']; ?>">FDC LOGISTICS</span>
                        <br />
                        <span class="<?php echo $loginStyle['logoSubtitle']; ?>">Beyond Distribution</span>
                    </div>
                </div>

                <hr class="<?php echo $loginStyle['hr']; ?>">
                <p class="<?php echo $loginStyle['heading']; ?> ">ENTER YOUR CREDENTIALS TO LOGIN</p>

                <div class="mb-4">
                    <input type="text" name="username" placeholder="Enter your System ID" class="<?php echo $loginStyle['form_input']; ?>" required>
                </div>

                <div class="mb-4">
                    <input type="password" name="password" placeholder="Enter your password" class="<?php echo $loginStyle['form_input']; ?>" required>
                </div>

                <div class="text-center mt-8">
                    <button type="submit" class="<?php echo $loginStyle['submit_button']; ?>">
                        Login
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="#" class="<?php echo $loginStyle['forgot_link']; ?>">Forgot password?</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>