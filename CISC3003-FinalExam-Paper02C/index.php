<?php
declare(strict_types=1);
session_start();

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C - Auth System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Scenario C - Account System</h1>
        <p>Signup, login, email activation, password reset, Ajax email validation.</p>
        <div class="actions">
            <a class="btn" href="register.php">Register</a>
            <a class="btn" href="login.php">Login</a>
        </div>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
