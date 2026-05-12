<?php
declare(strict_types=1);
require __DIR__ . "/connect.php";

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
$status = "";
$token = trim($_GET["token"] ?? ($_POST["token"] ?? ""));

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    if ($token === "") {
        $status = "Invalid token.";
    } elseif (strlen($password) < 8 || $password !== $confirm) {
        $status = "Password invalid or not matched.";
    } else {
        $stmt = $mysqli->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ? LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row || strtotime($row["expires_at"]) < time()) {
            $status = "Token invalid or expired.";
        } else {
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            $uid = (int) $row["user_id"];

            $upd = $mysqli->prepare("UPDATE users SET password_hash = ? WHERE id = ? LIMIT 1");
            $upd->bind_param("si", $new_hash, $uid);
            $upd->execute();
            $upd->close();

            $del = $mysqli->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $del->bind_param("i", $uid);
            $del->execute();
            $del->close();

            $status = "Password reset successful. You can now login.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Reset Password</h1>
        <?php if ($status !== ""): ?>
            <div class="notice"><?= htmlspecialchars($status) ?></div>
        <?php endif; ?>
        <form method="post" action="reset_password.php">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <label for="password">New Password</label>
            <input id="password" name="password" type="password" required minlength="8">

            <label for="confirm_password">Confirm New Password</label>
            <input id="confirm_password" name="confirm_password" type="password" required minlength="8">

            <button type="submit">Reset Password</button>
        </form>
        <p><a href="login.php">Back to login</a></p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
