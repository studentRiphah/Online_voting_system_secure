<?php
include ("./database.php");

session_start();

$user_id = $_SESSION['user_id']; // You must manage user sessions

// Fetch all parties
$party_query = $connection->query("SELECT * FROM parties ORDER BY id DESC");

// Fetch votes by current user
$votes_query = $connection->query("SELECT party_id FROM party_votes WHERE user_id = $user_id");
$voted_parties = [];
while ($row = $votes_query->fetch_assoc()) {
    $voted_parties[] = $row['party_id'];
}

$parties = [];
while ($party = $party_query->fetch_assoc()) {
    $party['voted'] = in_array($party['id'], $voted_parties);
    $parties[] = $party;
}

echo json_encode($parties);
?>
