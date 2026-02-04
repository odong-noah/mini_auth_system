<?php
// Changed from dbconn.php to dataconnect.php
require_once '../dataconnect.php';
$data = json_decode(file_get_contents("php://input"), true);

try {
    $u_clean = clean_string($data['username']);
    $e_clean = clean_string($data['email']);
    $r_clean = clean_string($data['role']);
    $p_hashed = password_hash($data['password'], PASSWORD_BCRYPT);

    // Updated: Table name 'mini_auth_users', columns 'mini_auth_id', 'mini_auth_username', 'mini_auth_email'
    $check = $conn->prepare("SELECT mini_auth_id FROM mini_auth_users WHERE mini_auth_username = :u OR mini_auth_email = :e LIMIT 1");
    $check->bindParam(':u', $u_clean);
    $check->bindParam(':e', $e_clean);
    $check->execute();

    if ($check->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Username or Email is already taken"]);
        exit;
    }

    // Updated: Table name and prefixed column names
    $st = $conn->prepare("INSERT INTO mini_auth_users (mini_auth_username, mini_auth_email, mini_auth_password, mini_auth_role) VALUES (:u, :e, :p, :r)");
    $st->bindParam(':u', $u_clean);
    $st->bindParam(':e', $e_clean);
    $st->bindParam(':p', $p_hashed);
    $st->bindParam(':r', $r_clean);
    
    $st->execute();
    echo json_encode(["success" => true, "message" => "Registration successful! Redirecting..."]);
} catch (Exception $e) { 
    die("ERROR R5512PQ"); 
}