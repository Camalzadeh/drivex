<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Queries.inc.php';

class UserRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare(Queries::FIND_USER_BY_EMAIL);
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $obj = $stmt->fetch();
        return $obj ?: null;
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare(Queries::FIND_USER_BY_ID);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $obj = $stmt->fetch();
        return $obj ?: null;
    }

    public function exists(string $username, string $email): bool {
        $stmt = $this->pdo->prepare(Queries::CHECK_USER_EXISTS);
        $stmt->execute([$username, $email]);
        return (bool)$stmt->fetch();
    }

    public function findLoginData(string $username): ?array {
        $stmt = $this->pdo->prepare(Queries::LOGIN_USER);
        $stmt->execute([$username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function create(string $username, string $email, string $passwordHash): int {
        $stmt = $this->pdo->prepare(Queries::REGISTER_USER);
        $stmt->execute([$username, $email, $passwordHash]);
        return (int)$this->pdo->lastInsertId();
    }
}
?>
