<?php
$servername = "localhost";
$username = "root";
$password = "humbet2006";
$dbname = "hwp_project";
$port = "3306";
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>