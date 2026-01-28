<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class StockLog extends Entity
{
    protected $datamap = [];
    protected $dates = ['created_at'];
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * Check if this is an incoming stock log
     */
    public function isIncoming(): bool
    {
        return $this->type === 'in';
    }

    /**
     * Check if this is an outgoing stock log
     */
    public function isOutgoing(): bool
    {
        return $this->type === 'out';
    }

    /**
     * Check if this is an adjustment log
     */
    public function isAdjustment(): bool
    {
        return $this->type === 'adjustment';
    }

    /**
     * Get formatted type label
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjustment' => 'Adjustment',
            default => 'Unknown',
        };
    }
}
