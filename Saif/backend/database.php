<?php
$servername="localhost";
$username="root";
$password="";
$dbname="voting_system";

$connection=mysqli_connect($servername,$username,$password,$dbname);
if ($connection->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}


?>