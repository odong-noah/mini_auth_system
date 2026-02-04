<?php
// Changed from dbconn.php to dataconnect.php
require_once '../dataconnect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

try {
    // Standard: BindParam requires a variable, not a function result.
    $user_clean = clean_string($data['username']);

    // Updated table name to 'mini_auth_users' and column to 'mini_auth_username'
    $st = $conn->prepare("SELECT * FROM mini_auth_users WHERE mini_auth_username = :u LIMIT 1");
    $st->bindParam(':u', $user_clean); 
    $st->execute();
    
    $u = $st->fetch(PDO::FETCH_ASSOC);

    // Updated column names: mini_auth_password, mini_auth_id, mini_auth_username, mini_auth_role
    if ($u && password_verify($data['password'], $u['mini_auth_password'])) {
        $_SESSION['user_id'] = $u['mini_auth_id'];
        $_SESSION['username'] = $u['mini_auth_username'];
        $_SESSION['role'] = $u['mini_auth_role'];
        echo json_encode(["success" => true, "redirect" => "auth_dashboard_sys.php"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    }
} catch (Exception $e) { 
    die("ERROR L3920HNS"); 
}