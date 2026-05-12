<?php
declare(strict_types=1);
require __DIR__ . "/connect.php";

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
$message = "Invalid activation token.";

$token = trim($_GET["token"] ?? "");
if ($token !== "") {
    $stmt = $mysqli->prepare("UPDATE users SET is_verified = 1, activation_token = NULL WHERE activation_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    if ($stmt->affected_rows === 1) {
        $message = "Your account has been activated. You can now login.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Activation Status</h1>
        <p><?= htmlspecialchars($message) ?></p>
        <p><a href="login.php">Go to login</a></p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
