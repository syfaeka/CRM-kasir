<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class TransactionDetail extends Entity
{
    protected $datamap = [];

    protected $dates = [];

    protected $casts = [
        'id' => 'integer',
        'transaction_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'float',
        'subtotal' => 'float',
    ];

    /**
     * Calculate and set subtotal from unit_price and quantity
     */
    public function calculateSubtotal(): static
    {
        $unitPrice = $this->attributes['unit_price'] ?? 0;
        $quantity = $this->attributes['quantity'] ?? 0;
        $this->attributes['subtotal'] = $unitPrice * $quantity;
        return $this;
    }

    /**
     * Set unit price and auto-calculate subtotal
     */
    public function setUnitPrice(float $price): static
    {
        $this->attributes['unit_price'] = $price;
        $this->calculateSubtotal();
        return $this;
    }

    /**
     * Set quantity and auto-calculate subtotal
     */
    public function setQuantity(int $quantity): static
    {
        $this->attributes['quantity'] = $quantity;
        $this->calculateSubtotal();
        return $this;
    }
}
