<?php
require_once '../dbconn.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

try {
    // Standard: BindParam requires a variable, not a function result.
    $user_clean = clean_string($data['username']);

    $st = $conn->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
    $st->bindParam(':u', $user_clean); 
    $st->execute();
    
    $u = $st->fetch(PDO::FETCH_ASSOC);

    if ($u && password_verify($data['password'], $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['username'] = $u['username'];
        $_SESSION['role'] = $u['role'];
        echo json_encode(["success" => true, "redirect" => "auth_dashboard_sys.php"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    }
} catch (Exception $e) { 
    die("ERROR L3920HNS"); 
}