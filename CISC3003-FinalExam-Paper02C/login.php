<?php
declare(strict_types=1);
session_start();
require __DIR__ . "/connect.php";

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
$status = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
        $status = "Enter valid email and password.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, name, password_hash, is_verified, created_at FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($password, $user["password_hash"])) {
            $status = "Invalid credentials.";
        } elseif ((int) $user["is_verified"] !== 1) {
            $status = "Please activate your account via email first.";
        } else {
            $_SESSION["user_id"] = (int) $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["member_since"] = $user["created_at"];
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <main class="container">
        <h1>Login</h1>
        <?php if ($status !== ""): ?>
            <div class="notice"><?= htmlspecialchars($status) ?></div>
        <?php endif; ?>

        <form id="loginForm" method="post" action="login.php">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required maxlength="150">

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required minlength="8">

            <button type="submit">Login</button>
        </form>
        <p><a href="forgot_password.php">Forgot password?</a></p>
        <p><a href="register.php">Create account</a></p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
