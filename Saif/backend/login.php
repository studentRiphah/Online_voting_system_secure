<?php
session_start();
include ("./database.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
// Get JSON input
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

// Basic validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    exit;
}

// Fetch user
$query = "SELECT id, first_name,last_name,phone,address, role , password, login_attempts, account_locked FROM users WHERE email = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if ($user['account_locked']) {
        echo json_encode(['status' => 'error', 'message' => 'Account is locked due to too many failed attempts.']);
        exit;
    }

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Reset attempts
        $resetQuery = "UPDATE users SET login_attempts = 0 WHERE id = " . $user['id'];
        mysqli_query($connection, $resetQuery);

        // Save session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['user_type'] = $user['role'];

        echo json_encode([
            'status' => 'success', 
            'message' => 'Login successful',
            'role' => $user['role']
        ]);
    } else {
        $attempts = $user['login_attempts'] + 1;
        $locked = ($attempts >= 5) ? 1 : 0;

        // Update attempts
        $updateQuery = "UPDATE users SET login_attempts = $attempts, account_locked = $locked WHERE id = " . $user['id'];
        mysqli_query($connection, $updateQuery);

        if ($locked) {
            echo json_encode(['status' => 'error', 'message' => 'Account locked after 5 failed attempts.']);
        } else {
            $left = 5 - $attempts;
            echo json_encode(['status' => 'error', 'message' => "Incorrect password. Attempts left: $left"]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or user not found.']);
}

?>
