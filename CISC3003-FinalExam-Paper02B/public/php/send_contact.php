<?php
declare(strict_types=1);
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");

$errors = [];
if ($name === "" || mb_strlen($name) > 100) {
    $errors[] = "Name is required and must be less than 100 chars.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required.";
}
if ($subject === "" || mb_strlen($subject) > 150) {
    $errors[] = "Subject is required and must be less than 150 chars.";
}
if ($message === "" || mb_strlen($message) > 1500) {
    $errors[] = "Message is required and must be less than 1500 chars.";
}

if (!empty($errors)) {
    $_SESSION["flash"] = [
        "type" => "error",
        "title" => "Validation failed",
        "message" => implode(" ", $errors)
    ];
    header("Location: ../index.php");
    exit;
}

$config = require __DIR__ . "/config.php";
$autoload = __DIR__ . "/../vendor/autoload.php";

if (!is_file($autoload)) {
    $_SESSION["flash"] = [
        "type" => "error",
        "title" => "PHPMailer not installed",
        "message" => "Run: composer require phpmailer/phpmailer in Scenario B project folder."
    ];
    header("Location: ../index.php");
    exit;
}

require $autoload;

$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
$smtp_debug_output = "";

if (!empty($config["debug_mode"])) {
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = static function (string $str, int $level) use (&$smtp_debug_output): void {
        $smtp_debug_output .= "[Level {$level}] {$str}\n";
    };
}

try {
    $mail->isSMTP();
    $mail->Host = $config["smtp_host"];
    $mail->SMTPAuth = true;
    $mail->Username = $config["smtp_username"];
    $mail->Password = $config["smtp_password"];
    $mail->SMTPSecure = $config["smtp_secure"];
    $mail->Port = (int) $config["smtp_port"];

    $mail->setFrom($config["from_email"], $config["from_name"]);
    $mail->addAddress($config["to_email"]);
    $mail->addReplyTo($email, $name);

    $mail->Subject = "[Scenario B] " . $subject;
    $mail->Body = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
    $mail->send();

    $_SESSION["flash"] = [
        "type" => "success",
        "title" => "Email sent",
        "message" => "Your message has been sent successfully (PRG pattern used).",
        "debug" => $smtp_debug_output
    ];
} catch (\PHPMailer\PHPMailer\Exception $e) {
    $_SESSION["flash"] = [
        "type" => "error",
        "title" => "Email send failed",
        "message" => $mail->ErrorInfo ?: $e->getMessage(),
        "debug" => $smtp_debug_output
    ];
}

// PRG pattern: POST -> Redirect -> GET
header("Location: ../index.php");
exit;
