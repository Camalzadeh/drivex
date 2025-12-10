<?php
session_start();
require_once "db.php";

$response = ["status" => "error", "message" => "Unknown action."];

if (!isset($_POST['action'])) {
    echo json_encode($response);
    exit;
}

$action = $_POST['action'];


// ============== REGISTER ==============
if ($action === "register") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match!"]);
        exit;
    }

    // check user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? OR email=? LIMIT 1");
    $stmt->execute([$username, $email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "User already exists!"]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $insert = $pdo->prepare("INSERT INTO users(username, email, password) VALUES(?,?,?)");
    $insert->execute([$username, $email, $hash]);

    echo json_encode(["status" => "success", "message" => "Account created successfully!"]);
    exit;
}



// ============== LOGIN ==============
if ($action === "login") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(["status" => "error", "message" => "Invalid username or password!"]);
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode(["status" => "success", "message" => "Login successful!"]);
    exit;
}

echo json_encode($response);
exit;
