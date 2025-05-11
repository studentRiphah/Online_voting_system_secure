<?php
session_start();
include("./database.php");
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$first_name = $data['full_name'] ?? '';
$last_name = $data['last_name'] ?? '';
$phone = $data['phone'] ?? '';
$address = $data['address'] ?? '';
$new_password = $data['new_password'] ?? '';

$errors = [];

if ($new_password) {
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    
    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit;
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET first_name=?, last_name=?, phone=?, address=?, password=? WHERE id=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssssi", $first_name, $last_name, $phone, $address, $hashed_password, $user_id);
} else {
    $query = "UPDATE users SET first_name=?, last_name=?, phone=?, address=? WHERE id=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssi", $first_name, $last_name, $phone, $address, $user_id);
}

if ($stmt->execute()) {
    $_SESSION['phone'] =  $phone;
    $_SESSION['address'] =  $address;
    echo json_encode(['status' => 'success','message' => 'Profile updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
}

$stmt->close();
$connection->close();
?>
