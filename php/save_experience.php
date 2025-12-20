<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location:index.php"); exit; }
require_once "db.php";

$user_id = $_SESSION['user_id'];

$date = $_POST['date'];
$startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];
$mileage = $_POST['mileage'];
$weather = $_POST['weather'];
$road = $_POST['roadType'];
$visibility = $_POST['visibility'];
$traffic = $_POST['traffic'];

$start = "$date $startTime:00";
$end = "$date $endTime:00";

$stmt = $conn->prepare("INSERT INTO DrivingSession (start_date,end_date,mileage,visibility_id,weather_condition_id,user_id) VALUES(?,?,?,?,?,?)");
$stmt->bind_param("ssdiis",$start,$end,$mileage,$visibility,$weather,$user_id);
$stmt->execute();
$session_id = $stmt->insert_id;

$conn->query("INSERT INTO OccursOn(session_id,road_type_id) VALUES($session_id,$road)");
$conn->query("INSERT INTO TakesPlace(session_id,traffic_condition_id) VALUES($session_id,$traffic)");

header("Location: home.php");
exit;
?>
