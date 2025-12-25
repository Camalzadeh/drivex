<?php

class User {
    private ?int $user_id;
    private ?string $username;
    private ?string $email;
    private ?string $password_hash;
    private ?string $created_at;


    
    public function getId(): ?int { return $this->user_id; }
    public function getUsername(): ?string { return $this->username; }
    public function getEmail(): ?string { return $this->email; }
    public function getCreatedAt(): ?string { return $this->created_at; }
}
?>
