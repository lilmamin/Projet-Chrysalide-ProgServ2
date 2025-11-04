<?php

require_once __DIR__ . '/Role.php';

class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $password_hash;
    private Role $role;
    private DateTime $created_at;

    public function __construct(
        string $username,
        string $email,
        string $password_hash,
        ?Role $role = null,
        ?DateTime $created_at = null,
        ?int $id = null
    ) {
        $this->id = $id ?? 0;
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->role = $role ?? Role::Reader;
        $this->created_at = $created_at ?? new DateTime();
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    // Méthodes utilitaires
    public function isAuthor(): bool
    {
        return $this->role === Role::Author;
    }

    public function isReader(): bool
    {
        return $this->role === Role::Reader;
    }

    public function promoteToAuthor(): void
    {
        $this->role = Role::Author;
    }

    public function getInfo(): string
    {
        return "{$this->username} ({$this->role->getLabel()}) – inscrit·e le {$this->created_at->format('Y-m-d')}";
    }
}
