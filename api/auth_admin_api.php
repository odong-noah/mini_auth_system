<?php
header('Content-Type: application/json');
require_once '../dataconnect.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Access denied"]);
    exit;
}

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

try {
    // LIST USERS
    if ($action === 'list') {
        $st = $conn->prepare("
            SELECT mini_auth_id, mini_auth_username, mini_auth_email, mini_auth_role
            FROM mini_auth_users
            ORDER BY mini_auth_id DESC
        ");
        $st->execute();
        $users = $st->fetchAll(PDO::FETCH_ASSOC);

        $users_mapped = array_map(function($u) {
            return [
                'id' => $u['mini_auth_id'],
                'username' => $u['mini_auth_username'],
                'email' => $u['mini_auth_email'],
                'role' => $u['mini_auth_role']
            ];
        }, $users);

        echo json_encode(["success" => true, "users" => $users_mapped]);
        exit;
    }

    // ADD OR UPDATE USER
    if ($action === 'add' || $action === 'update') {
        $u = clean_string($data['username']);
        $e = clean_string($data['email']);
        $r = clean_string($data['role']);

        if ($action === 'add') {
            $p = password_hash($data['password'], PASSWORD_BCRYPT);
            $st = $conn->prepare("
                INSERT INTO mini_auth_users (mini_auth_username, mini_auth_email, mini_auth_password, mini_auth_role)
                VALUES (:u, :e, :p, :r)
            ");
            $st->bindParam(':u', $u);
            $st->bindParam(':e', $e);
            $st->bindParam(':p', $p);
            $st->bindParam(':r', $r);
            $st->execute();

        } else { // update
            $sql = "UPDATE mini_auth_users SET mini_auth_username=:u, mini_auth_email=:e, mini_auth_role=:r";
            if (!empty($data['password'])) {
                $sql .= ", mini_auth_password=:p";
            }
            $sql .= " WHERE mini_auth_id=:id";
            $st = $conn->prepare($sql);

            $st->bindParam(':u', $u);
            $st->bindParam(':e', $e);
            $st->bindParam(':r', $r);
            $st->bindParam(':id', $data['id']);
            if (!empty($data['password'])) {
                $p = password_hash($data['password'], PASSWORD_BCRYPT);
                $st->bindParam(':p', $p);
            }

            $st->execute();
        }

        echo json_encode(["success" => true]);
        exit;
    }

    // DELETE USER
    if ($action === 'delete') {
        if ($data['id'] == $_SESSION['user_id']) {
            echo json_encode(["success" => false, "message" => "Cannot delete your own account"]);
            exit;
        }

        $st = $conn->prepare("DELETE FROM mini_auth_users WHERE mini_auth_id=:id");
        $st->bindParam(':id', $data['id']);
        $st->execute();

        echo json_encode(["success" => true]);
        exit;
    }

    echo json_encode(["success" => false, "message" => "Invalid action"]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Server error", "error"=>$e->getMessage()]);
}
?>
