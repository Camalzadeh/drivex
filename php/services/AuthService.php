<?php

require_once __DIR__ . '/../repositories/UserRepository.php';

class AuthService {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register(string $username, string $email, string $password, string $confirmPassword): int {
        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match");
        }

        if ($this->userRepository->exists($username, $email)) {
            throw new Exception("Username or Email already exists");
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $this->userRepository->create($username, $email, $hash);
    }

    public function login(string $username, string $password): ?array {
        $user = $this->userRepository->findLoginData($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }
    
    public function getUserProfile(int $userId): ?User {
        return $this->userRepository->findById($userId);
    }
}
?>
