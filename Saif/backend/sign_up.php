<?php
include ("./database.php");

header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

// Sanitize & validate
$firstName = trim($data['firstName'] ?? '');
$lastName = trim($data['lastName'] ?? '');
$email = trim($data['email'] ?? '');
$userType = $data['userType'] ?? '';
$password = $data['password'] ?? '';
$confirmPassword = $data['confirmPassword'] ?? '';

$errors = [];

if (empty($firstName) || !preg_match("/^[a-zA-Z]+$/", $firstName)) {
    $errors[] = "First name is required and must be alphabetic.";
}

if (empty($lastName) || !preg_match("/^[a-zA-Z]+$/", $lastName)) {
    $errors[] = "Last name is required and must be alphabetic.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

$validUserTypes = ['canidate', 'party', 'admin'];
if (!in_array($userType, $validUserTypes)) {
    $errors[] = "Invalid user type selected.";
}

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

// Check if email already exists
$query1 = "SELECT id FROM users WHERE email = '$email'";
$result1 = mysqli_query($connection, $query1);

if (mysqli_num_rows($result1) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already registered.']);
    exit; // Stop further execution
}

// Proceed to insert
$hashedPassword = $password;
$query2 = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `role`, `password`) 
           VALUES ('$firstName', '$lastName', '$email', '$userType', '$hashedPassword')";

$result2 = mysqli_query($connection, $query2);

if ($result2) {
    echo json_encode(['status' => 'success', 'message' => 'Account created successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create account.']);
}


?>
