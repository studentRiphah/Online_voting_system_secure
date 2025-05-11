<?php
include ("./database.php");
session_start();

$user_id = $_SESSION['user_id'];
$party_id = $_POST['party_id'];

// Check if already voted
$check = $connection->query("SELECT * FROM party_votes WHERE user_id = $user_id AND party_id = $party_id");
if ($check->num_rows > 0) {
    echo json_encode(['status' => 'already_voted']);
    exit;
}

// Insert vote
$connection->query("INSERT INTO party_votes (user_id, party_id) VALUES ($user_id, $party_id)");

// Update party vote count
//$connection->query("UPDATE parties SET votes = votes + 1 WHERE id = $party_id");
$stmt = $connection->prepare("UPDATE parties SET votes = votes + 1 WHERE id = ?");
$stmt->bind_param("i", $party_id);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
