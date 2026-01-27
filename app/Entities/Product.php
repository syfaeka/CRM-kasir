<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Product extends Entity
{
    protected $datamap = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'price' => 'float',
        'stock' => 'integer',
    ];

    /**
     * Check if product has sufficient stock
     */
    public function hasStock(int $quantity): bool
    {
        return ($this->attributes['stock'] ?? 0) >= $quantity;
    }

    /**
     * Deduct stock
     */
    public function deductStock(int $quantity): static
    {
        $currentStock = $this->attributes['stock'] ?? 0;
        $this->attributes['stock'] = max(0, $currentStock - $quantity);
        return $this;
    }

    /**
     * Add stock
     */
    public function addStock(int $quantity): static
    {
        $this->attributes['stock'] = ($this->attributes['stock'] ?? 0) + $quantity;
        return $this;
    }

    /**
     * Calculate subtotal for given quantity
     */
    public function calculateSubtotal(int $quantity): float
    {
        return ($this->attributes['price'] ?? 0) * $quantity;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        return ($this->attributes['stock'] ?? 0) > 0;
    }
}
