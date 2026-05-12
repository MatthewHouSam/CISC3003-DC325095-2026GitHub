<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
$user_name = $_SESSION["user_name"] ?? "User";
$member_since = $_SESSION["member_since"] ?? date("Y-m-d H:i:s");
$display_name = trim((string) preg_replace('/^[^A-Za-z]+/', '', (string) $user_name));
if ($display_name === '') {
    $display_name = 'User';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/dashboad.css">
</head>
<body>
    <main class="card">
        <div class="header-row">
            <h1>Welcome, <?= htmlspecialchars($display_name) ?></h1>
            <a class="btn logout" href="logout.php">Logout</a>
        </div>

        <p class="meta">Member since: <strong><?= htmlspecialchars((string) $member_since) ?></strong></p>

        <section class="services">
            <h2>Available Services</h2>
            <ul>
                <li>View your account summary</li>
                <li>Access secured member area</li>
                <li>Reset your password from account tools</li>
            </ul>
        </section>

        <p class="hint">Tip: capture this page as evidence for Scenario C.09.</p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
