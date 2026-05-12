<?php
declare(strict_types=1);

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B Dashboard</title>
    <link rel="stylesheet" href="css/dashboad.css">
</head>
<body>
    <main class="card">
        <h1>Scenario B Dashboard</h1>
        <p>This page is included to match the required deliverable file list.</p>
        <p><a href="index.php">Back to Scenario B contact form</a></p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
