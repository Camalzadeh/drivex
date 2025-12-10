<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
<h1>Welcome, <?= htmlspecialchars($username) ?></h1>

<a href="logout.php">Logout</a>
</body>
</html>
