<?php
session_start();
require_once "db.php";

$response = ["status"=>"error","message"=>"Unknown action."];

if (!isset($_POST['action'])) { echo json_encode($response); exit; }
$action = $_POST['action'];

if ($action === "register") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo json_encode(["status"=>"error","message"=>"Passwords do not match!"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM Users WHERE username=? LIMIT 1");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows>0) {
        echo json_encode(["status"=>"error","message"=>"User already exists!"]);
        exit;
    }

    $hash = password_hash($password,PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO Users(username,password) VALUES(?,?)");
    $stmt->bind_param("ss",$username,$hash);
    $stmt->execute();

    echo json_encode(["status"=>"success","message"=>"Account created successfully!"]);
    exit;
}

if ($action === "login") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE username=? LIMIT 1");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if (!$user || !password_verify($password,$user['password'])) {
        echo json_encode(["status"=>"error","message"=>"Invalid username or password!"]);
        exit;
    }

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode(["status"=>"success","message"=>"Login successful!"]);
    exit;
}
?>
