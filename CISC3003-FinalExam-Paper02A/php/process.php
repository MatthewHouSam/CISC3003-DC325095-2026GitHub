<?php
declare(strict_types=1);

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$age = $_POST["age"] ?? "";
$message = trim($_POST["message"] ?? "");
$programme = trim($_POST["programme"] ?? "");
$study_mode = trim($_POST["study_mode"] ?? "");
$skills = $_POST["skills"] ?? [];

$errors = [];

if ($full_name === "" || mb_strlen($full_name) > 100) {
    $errors[] = "Full Name is required and must be at most 100 characters.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email is required.";
}

$age_number = filter_var($age, FILTER_VALIDATE_INT, ["options" => ["min_range" => 16, "max_range" => 99]]);
if ($age_number === false) {
    $errors[] = "Age must be between 16 and 99.";
}

if ($message === "" || mb_strlen($message) > 600) {
    $errors[] = "Self Introduction is required and must be at most 600 characters.";
}

$valid_programmes = ["Computer Science", "Data Science", "Information Systems"];
if (!in_array($programme, $valid_programmes, true)) {
    $errors[] = "Please select a valid programme.";
}

$valid_study_modes = ["Full-time", "Part-time"];
if (!in_array($study_mode, $valid_study_modes, true)) {
    $errors[] = "Please choose a valid study mode.";
}

$valid_skills = ["PHP", "MySQL", "JavaScript"];
$skills = array_values(array_intersect($valid_skills, (array) $skills));
$skills_csv = implode(", ", $skills);

if (!empty($errors)) {
    http_response_code(422);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Validation Errors</title>
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>
        <main class="container">
            <h1>Validation Errors</h1>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <p><a href="../index.php">Back to form</a></p>
        </main>
        <footer>
            CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
        </footer>
    </body>
    </html>
    <?php
    exit;
}

require __DIR__ . "/connect.php";

// Prepared statement prevents SQL injection by separating SQL and data.
$sql = "INSERT INTO student_forms (full_name, email, age, message, programme, study_mode, skills) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("ssissss", $full_name, $email, $age_number, $message, $programme, $study_mode, $skills_csv);
$ok = $stmt->execute();
$insert_id = $stmt->insert_id;
$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Result</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <?php if ($ok): ?>
            <h1>Record Saved Successfully</h1>
            <p>Your record ID is: <strong><?= (int) $insert_id ?></strong></p>
        <?php else: ?>
            <h1>Insert Failed</h1>
            <p>Unable to save your data. Please check database setup.</p>
        <?php endif; ?>
        <p><a href="../index.php">Back to form</a></p>
    </main>
    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
