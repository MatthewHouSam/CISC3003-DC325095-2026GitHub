<?php
declare(strict_types=1);
session_start();

require __DIR__ . "/connect.php";
require __DIR__ . "/php/mailer.php";

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
$status = "";
$debug = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    $errors = [];
    if ($name === "" || mb_strlen($name) > 120) {
        $errors[] = "Name is required (max 120 chars).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if ($password !== $confirm) {
        $errors[] = "Password confirmation does not match.";
    }

    if (empty($errors)) {
        $check_stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $exists = $check_stmt->get_result()->fetch_assoc();
        $check_stmt->close();

        if ($exists) {
            $errors[] = "Email already exists.";
        }
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $activation_token = bin2hex(random_bytes(32));

        $insert_stmt = $mysqli->prepare("INSERT INTO users (name, email, password_hash, activation_token, is_verified) VALUES (?, ?, ?, ?, 0)");
        $insert_stmt->bind_param("ssss", $name, $email, $password_hash, $activation_token);
        $ok = $insert_stmt->execute();
        $insert_stmt->close();

        if ($ok) {
            $mail_cfg = require __DIR__ . "/php/mail_config.php";
            $link = rtrim($mail_cfg["app_base_url"], "/") . "/activate.php?token=" . urlencode($activation_token);
            $subject = "Activate your CISC3003 account";
            $body = "Hi {$name},\n\nPlease activate your account by clicking:\n{$link}\n\nIf you did not register, ignore this email.";

            $sent = send_project_mail($email, $name, $subject, $body, $debug);
            if ($sent) {
                $status = "Register success. Activation email sent.";
            } else {
                $status = "Registered, but activation email failed. Debug shown below.";
            }
        } else {
            $errors[] = "Registration failed. Please check database setup.";
        }
    }

    if (!empty($errors)) {
        $status = implode(" ", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <main class="container">
        <h1>Create Account</h1>
        <?php if ($status !== ""): ?>
            <div class="notice"><?= htmlspecialchars($status) ?></div>
        <?php endif; ?>
        <?php if ($debug !== ""): ?>
            <pre><?= htmlspecialchars($debug) ?></pre>
        <?php endif; ?>

        <form id="registerForm" method="post" action="register.php">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" required maxlength="120">

            <label for="email">Email</label>
            <input id="email" name="email" type="email" required maxlength="150">
            <small id="emailCheck"></small>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required minlength="8">

            <label for="confirm_password">Confirm Password</label>
            <input id="confirm_password" name="confirm_password" type="password" required minlength="8">

            <button type="submit">Register</button>
        </form>
        <p><a href="login.php">Already have account? Login</a></p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
