<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Extracker - Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<h1>Welcome, <?= htmlspecialchars($username) ?> 👋</h1>

<p>This is your dashboard page.</p>

<a href="logout.php">Logout</a>

</body>
</html>
