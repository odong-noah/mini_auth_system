<?php
session_start();
date_default_timezone_set('America/Los_Angeles');

$dbname = "mini_auth_system";
$servername = "localhost";
$Username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $Username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR J3DJS7SGHSJS");
}

function clean_string($string_val) {
    if ($string_val === null) { return ""; }
    return strip_tags(filter_var($string_val, FILTER_SANITIZE_SPECIAL_CHARS));
}
?>
