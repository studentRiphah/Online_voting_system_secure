<?php
session_start();
include ("./database.php");
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$query1 = "SELECT * FROM parties WHERE user_id = '$user_id'";
$result1 = mysqli_query($connection, $query1);

if (mysqli_num_rows($result1) > 0) {
    $party = mysqli_fetch_assoc($result1);
    echo json_encode([
        'status' => 'success',
        'data' => $party
    ]);
} else {
    echo json_encode([
        'status' => 'empty',
        'message' => 'No party registered yet.'
    ]);
}
?>