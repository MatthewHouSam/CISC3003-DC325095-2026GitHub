<?php
declare(strict_types=1);

$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "cisc3003_paper02a";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
