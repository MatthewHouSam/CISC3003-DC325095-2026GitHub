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
    $email = trim($_POST["email"] ?? "");
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = "Please enter a valid email.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, name FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires_at = date("Y-m-d H:i:s", time() + 3600);
            $uid = (int) $user["id"];

            $del = $mysqli->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $del->bind_param("i", $uid);
            $del->execute();
            $del->close();

            $ins = $mysqli->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $ins->bind_param("iss", $uid, $token, $expires_at);
            $ins->execute();
            $ins->close();

            $mail_cfg = require __DIR__ . "/php/mail_config.php";
            $link = rtrim($mail_cfg["app_base_url"], "/") . "/reset_password.php?token=" . urlencode($token);
            $subject = "Reset your password";
            $body = "Hello {$user['name']},\n\nReset your password using this link (valid 1 hour):\n{$link}";
            send_project_mail($email, $user["name"], $subject, $body, $debug);
        }

        $status = "If the email exists, a reset link has been sent.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Forgot Password</h1>
        <?php if ($status !== ""): ?>
            <div class="notice"><?= htmlspecialchars($status) ?></div>
        <?php endif; ?>
        <?php if ($debug !== ""): ?>
            <pre><?= htmlspecialchars($debug) ?></pre>
        <?php endif; ?>
        <form method="post" action="forgot_password.php">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required maxlength="150">
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to login</a></p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
