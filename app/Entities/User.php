<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'role' => 'string',
    ];

    /**
     * Hash password before saving
     */
    public function setPassword(string $password): static
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->attributes['password']);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->attributes['role'] === 'admin';
    }

    /**
     * Check if user is cashier
     */
    public function isCashier(): bool
    {
        return $this->attributes['role'] === 'cashier';
    }
}
