<?php

$host = "localhost:4306";
$user = "root";
$pass = "";
$db   = "business_rating_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

?>