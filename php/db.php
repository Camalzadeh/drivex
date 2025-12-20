<?php
$host = "localhost";
$user = "root";
$pass = "humbet2006";
$db = "hwp_project";
$port = "3306";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>