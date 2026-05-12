<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function send_project_mail(string $to_email, string $to_name, string $subject, string $body, string &$debug_text = ""): bool
{
    $config = require __DIR__ . "/mail_config.php";
    $autoload = __DIR__ . "/../vendor/autoload.php";
    if (!is_file($autoload)) {
        $debug_text = "Missing vendor/autoload.php. Run composer install.";
        return false;
    }

    require_once $autoload;

    $mail = new PHPMailer(true);
    if (!empty($config["debug_mode"])) {
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = static function (string $str, int $level) use (&$debug_text): void {
            $debug_text .= "[Level {$level}] {$str}\n";
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
        $mail->addAddress($to_email, $to_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        $debug_text .= "Mail error: " . $mail->ErrorInfo . " / " . $e->getMessage();
        return false;
    }
}
