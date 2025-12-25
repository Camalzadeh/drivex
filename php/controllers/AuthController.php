<?php

require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../config/db.inc.php';

class AuthController {
    private AuthService $authService;

    public function __construct(PDO $pdo) {
        $userRepo = new UserRepository($pdo);
        $this->authService = new AuthService($userRepo);
    }

    public function login(array $postData): void {
        $username = trim($postData['username'] ?? '');
        $password = $postData['password'] ?? '';

        $user = $this->authService->login($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: ../index.php");
            exit;
        }
    }

    public function register(array $postData): void {
        $username = trim($postData['username'] ?? '');
        $email = trim($postData['email'] ?? '');
        $password = $postData['password'] ?? '';
        $confirm = $postData['confirm_password'] ?? '';

        try {
            $newUserId = $this->authService->register($username, $email, $password, $confirm);
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['username'] = $username;
            header("Location: ../dashboard.php");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = "Registration failed: " . $e->getMessage();
            header("Location: ../index.php");
            exit;
        }
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        header("Location: ../index.php");
        exit;
    }
}
?>
