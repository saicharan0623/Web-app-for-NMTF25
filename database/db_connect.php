<?php
$servername = "localhost";
$username = "nmimstec_supportteam";  
$password = "nmimstechfiesta";
$dbname = "nmimstec_hfest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
