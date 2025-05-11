<?php
session_start();
include ("./database.php");

header('Content-Type: application/json');
// Get POST data
$data = json_decode(file_get_contents("php://input"), true);


$party_name = trim($data['partyName'] ?? '');
$leader_founder = trim($data['leaderFounder'] ?? '');
$phone_contact = trim($data['contactPhone'] ?? '');
$email = trim($data['email'] ?? '');
$party_quote = trim($data['partyQuote'] ?? '');
$headquarter = trim($data['headquarter'] ?? '');
$party_description = trim($data['partyDescription'] ?? '');



$errors = [];

if (empty($party_name)) {
    $errors[] = "Party name is required and must be alphabetic.";
}

if (empty($leader_founder)) {
    $errors[] = "Founder name is required and must be alphabetic.";
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

if (empty($phone_contact)) {
    $errors[] = "Party contact is required.";
}

if (empty($party_quote)) {
    $errors[] = "Party qoute is required.";
}

if (empty($headquarter)) {
    $errors[] = "Party headquater is required.";
}

if (empty($party_description)) {
    $errors[] = "Party discription is required.";
}

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'errors' => $errors]);
    exit;
}

// Ensure only one party per user
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$query1 = "SELECT id FROM parties WHERE user_id = '$user_id'";
$result1 = mysqli_query($connection, $query1);

if (mysqli_num_rows($result1) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already created a party.']);
    exit;
}

// Insert new party
$query2 = "INSERT INTO parties (user_id, party_name, leader_founder, phone_contact, email, party_quote, headquarter, party_description)
           VALUES ('$user_id', '$party_name', '$leader_founder', '$phone_contact', '$email', '$party_quote', '$headquarter', '$party_description')";

$result2 = mysqli_query($connection, $query2);

if ($result2) {
    echo json_encode(['status' => 'success', 'message' => 'Party created successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create party.']);
}
?>