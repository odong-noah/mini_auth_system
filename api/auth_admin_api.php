<?php
header('Content-Type: application/json');
require_once '../dbconn.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(["success" => false]));
}

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

if ($action == 'list') {
    $st = $conn->prepare("SELECT id, username, email, role FROM users ORDER BY created_at DESC");
    $st->execute();
    echo json_encode($st->fetchAll(PDO::FETCH_ASSOC));
}

if ($action == 'add' || $action == 'update') {
    $u = clean_string($data['username']);
    $e = clean_string($data['email']);
    $r = clean_string($data['role']);
    
    if ($action == 'add') {
        $p = password_hash($data['password'], PASSWORD_BCRYPT);
        $st = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:u, :e, :p, :r)");
        $st->bindParam(':u', $u);
        $st->bindParam(':e', $e);
        $st->bindParam(':p', $p);
        $st->bindParam(':r', $r);
    } else {
        $sql = "UPDATE users SET username=:u, email=:e, role=:r";
        if (!empty($data['password'])) { $sql .= ", password=:p"; }
        $sql .= " WHERE id=:id";
        $st = $conn->prepare($sql);
        $st->bindParam(':u', $u);
        $st->bindParam(':e', $e);
        $st->bindParam(':r', $r);
        $st->bindParam(':id', $data['id']);
        if (!empty($data['password'])) {
            $p = password_hash($data['password'], PASSWORD_BCRYPT);
            $st->bindParam(':p', $p);
        }
    }
    $st->execute();
    echo json_encode(["success" => true]);
}

if ($action == 'delete') {
    $st = $conn->prepare("DELETE FROM users WHERE id = :id AND id != :my_id");
    $st->execute([':id' => $data['id'], ':my_id' => $_SESSION['user_id']]);
    echo json_encode(["success" => true]);
}