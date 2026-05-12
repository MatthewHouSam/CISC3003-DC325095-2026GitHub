<?php
declare(strict_types=1);
session_start();

$footer_name = "CHUNG HOU SAM";
$footer_student_id = "DC325095";
$flash = $_SESSION["flash"] ?? null;
unset($_SESSION["flash"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B - Contact Form</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <main class="container">
        <h1>Scenario B - Contact Form</h1>
        <p>Includes client-side validation, PHPMailer, debug output, and PRG pattern.</p>

        <?php if ($flash): ?>
            <div class="flash <?= htmlspecialchars($flash["type"]) ?>">
                <strong><?= htmlspecialchars($flash["title"]) ?></strong>
                <p><?= htmlspecialchars($flash["message"]) ?></p>
                <?php if (!empty($flash["debug"])): ?>
                    <pre><?= htmlspecialchars($flash["debug"]) ?></pre>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form id="contactForm" method="post" action="php/send_contact.php">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" required minlength="2" maxlength="100">

            <label for="email">Email</label>
            <input id="email" name="email" type="email" required maxlength="150">

            <label for="subject">Subject</label>
            <input id="subject" name="subject" type="text" required minlength="3" maxlength="150">

            <label for="message">Message</label>
            <textarea id="message" name="message" required minlength="10" maxlength="1500" rows="6"></textarea>

            <button type="submit">Send Message</button>
        </form>
    </main>

    <footer>
        CISC3003 Web Programming: <?= htmlspecialchars($footer_name) ?> + <?= htmlspecialchars($footer_student_id) ?> + 2026
    </footer>
</body>
</html>
