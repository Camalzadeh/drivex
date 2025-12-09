<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if ($conn === null) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $data) {

    $action = $data['action'] ?? '';

    // LOGIN Prosesi (Username ilə)
    if ($action === 'login') {
        $username = $data['username'] ?? ''; // İndi Username oxunur
        $password = $data['password'] ?? '';

        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username and password are required']);
            exit;
        }

        try {
            // Username ilə istifadəçini DB-dən axtarırıq
            $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                // Başarılı Giriş
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Login successful']);
                exit;
            } else {
                // İstifadəçi tapılmadı VƏ YA şifrə yanlışdır
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
                exit;
            }
        } catch (PDOException $e) {
            error_log("Login DB Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An internal server error occurred']);
            exit;
        }
    }

    // REGISTER Prosesi
    elseif ($action === 'register') {
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }
        if ($password !== $confirmPassword) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
            exit;
        }

        try {
            // Username və ya Emailin artıq mövcud olub-olmadığını yoxlayın
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->fetch()) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'Username or Email already registered']);
                exit;
            }

            // Şifrəni təhlükəsiz şəkildə hash-ləyin
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // İstifadəçini DB-yə daxil edin
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword]);

            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Registration successful.']);
            exit;

        } catch (PDOException $e) {
            error_log("Register DB Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An internal server error occurred during registration']);
            exit;
        }
    }
}

// Digər metodlar
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method or data not valid']);
?>