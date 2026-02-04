<?php
require_once '../dbconn.php';
$data = json_decode(file_get_contents("php://input"), true);

try {
    $u_clean = clean_string($data['username']);
    $e_clean = clean_string($data['email']);
    $r_clean = clean_string($data['role']);
    $p_hashed = password_hash($data['password'], PASSWORD_BCRYPT);

    // Standard: Check for identity conflict first
    $check = $conn->prepare("SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1");
    $check->bindParam(':u', $u_clean);
    $check->bindParam(':e', $e_clean);
    $check->execute();

    if ($check->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Username or Email is already taken"]);
        exit;
    }

    $st = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:u, :e, :p, :r)");
    $st->bindParam(':u', $u_clean);
    $st->bindParam(':e', $e_clean);
    $st->bindParam(':p', $p_hashed);
    $st->bindParam(':r', $r_clean);
    
    $st->execute();
    echo json_encode(["success" => true, "message" => "Registration successful! Redirecting..."]);
} catch (Exception $e) { 
    die("ERROR R5512PQ"); 
}