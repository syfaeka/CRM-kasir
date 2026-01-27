<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Customer extends Entity
{
    protected $datamap = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'points' => 'integer',
    ];

    /**
     * Add loyalty points
     */
    public function addPoints(int $points): static
    {
        $this->attributes['points'] = ($this->attributes['points'] ?? 0) + $points;
        return $this;
    }

    /**
     * Deduct loyalty points (for redemption)
     */
    public function deductPoints(int $points): static
    {
        $currentPoints = $this->attributes['points'] ?? 0;
        $this->attributes['points'] = max(0, $currentPoints - $points);
        return $this;
    }

    /**
     * Check if customer has enough points
     */
    public function hasEnoughPoints(int $requiredPoints): bool
    {
        return ($this->attributes['points'] ?? 0) >= $requiredPoints;
    }

    /**
     * Calculate points from transaction amount
     * 1 point per 10,000 IDR spent
     */
    public static function calculatePointsFromAmount(float $amount): int
    {
        return (int) floor($amount / 10000);
    }
}
