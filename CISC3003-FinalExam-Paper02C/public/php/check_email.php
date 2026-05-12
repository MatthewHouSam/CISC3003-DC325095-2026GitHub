<?php
declare(strict_types=1);
header("Content-Type: application/json; charset=utf-8");

require __DIR__ . "/../connect.php";

$email = trim($_GET["email"] ?? "");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["ok" => false, "message" => "Invalid email format"]);
    exit;
}

$stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc() ? true : false;
$stmt->close();

echo json_encode([
    "ok" => true,
    "exists" => $exists,
    "message" => $exists ? "Email already registered" : "Email is available"
]);
