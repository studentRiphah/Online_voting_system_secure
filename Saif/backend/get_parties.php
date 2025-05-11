<?php
include ("./database.php");
session_start();

$user_id = $_SESSION['user_id'];

$result = $connection->query("SELECT * FROM parties ORDER BY id DESC");

$parties = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $parties[] = $row;
    }
}

echo json_encode($parties);
?>
