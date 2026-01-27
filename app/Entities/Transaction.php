<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Transaction extends Entity
{
    protected $datamap = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'customer_id' => '?integer',
        'user_id' => 'integer',
        'total_amount' => 'float',
        'points_earned' => 'integer',
    ];

    /**
     * Generate unique invoice number
     * Format: INV-YYYYMMDD-XXXXXX (random 6 chars)
     */
    public static function generateInvoiceNumber(): string
    {
        $date = date('Ymd');
        $random = strtoupper(bin2hex(random_bytes(3)));
        return "INV-{$date}-{$random}";
    }

    /**
     * Set invoice number automatically if not set
     */
    public function setInvoiceNumber(?string $invoiceNumber = null): static
    {
        $this->attributes['invoice_number'] = $invoiceNumber ?? self::generateInvoiceNumber();
        return $this;
    }

    /**
     * Calculate points earned from total amount
     */
    public function calculatePointsEarned(): int
    {
        return Customer::calculatePointsFromAmount($this->attributes['total_amount'] ?? 0);
    }

    /**
     * Check if transaction has associated customer
     */
    public function hasCustomer(): bool
    {
        return !empty($this->attributes['customer_id']);
    }
}
