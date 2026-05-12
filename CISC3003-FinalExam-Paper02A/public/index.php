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
    <title>Scenario A - Form and MySQL</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <main class="container">
        <h1>Scenario A - Student Information Form</h1>
        <p>Submit this form to demonstrate PHP validation and safe MySQL insert.</p>

        <form action="php/process.php" method="post" novalidate>
            <label for="full_name">Full Name</label>
            <input id="full_name" name="full_name" type="text" required maxlength="100">

            <label for="email">Email</label>
            <input id="email" name="email" type="email" required maxlength="120">

            <label for="age">Age</label>
            <input id="age" name="age" type="number" min="16" max="99" required>

            <label for="message">Self Introduction</label>
            <textarea id="message" name="message" rows="4" maxlength="600" required></textarea>

            <label for="programme">Programme</label>
            <select id="programme" name="programme" required>
                <option value="">-- Select One --</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Data Science">Data Science</option>
                <option value="Information Systems">Information Systems</option>
            </select>

            <fieldset>
                <legend>Study Mode</legend>
                <label><input type="radio" name="study_mode" value="Full-time" required> Full-time</label>
                <label><input type="radio" name="study_mode" value="Part-time"> Part-time</label>
            </fieldset>

            <fieldset>
                <legend>Skills</legend>
                <label><input type="checkbox" name="skills[]" value="PHP"> PHP</label>
                <label><input type="checkbox" name="skills[]" value="MySQL"> MySQL</label>
                <label><input type="checkbox" name="skills[]" value="JavaScript"> JavaScript</label>
            </fieldset>

            <button type="submit">Submit Form</button>
        </form>
    </main>

    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
