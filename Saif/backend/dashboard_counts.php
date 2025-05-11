<?php
include ("./database.php");

// Total Parties
$totalPartiesResult = $connection->query("SELECT COUNT(*) AS total_parties FROM parties");
$totalParties = $totalPartiesResult->fetch_assoc()['total_parties'];

// Total Candidates
$totalCandidatesResult = $connection->query("SELECT COUNT(*) AS total_candidates FROM users WHERE role = 'canidate'");
$totalCandidates = $totalCandidatesResult->fetch_assoc()['total_candidates'];

// Total Votes
$totalVotesResult = $connection->query("SELECT SUM(votes) AS total_votes FROM parties");
$totalVotes = $totalVotesResult->fetch_assoc()['total_votes'];

echo json_encode([
    'total_parties' => $totalParties,
    'total_candidates' => $totalCandidates,
    'total_votes' => $totalVotes ? $totalVotes : 0
]);
?>
