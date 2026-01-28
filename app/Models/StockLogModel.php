<?php

namespace App\Models;

use App\Entities\StockLog;
use CodeIgniter\Model;

class StockLogModel extends Model
{
    protected $table = 'stock_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = StockLog::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'product_id',
        'type',
        'quantity',
        'note',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = ''; // No updated_at field

    // Validation
    protected $validationRules = [
        'product_id' => 'required|integer',
        'type' => 'required|in_list[in,out,adjustment]',
        'quantity' => 'required|integer',
    ];

    protected $skipValidation = false;

    /**
     * Get logs by product
     */
    public function getByProduct(int $productId, int $limit = 50): array
    {
        return $this->where('product_id', $productId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get logs by type
     */
    public function getByType(string $type, int $limit = 50): array
    {
        return $this->where('type', $type)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get logs by date range
     */
    public function getByDateRange(string $startDate, string $endDate): array
    {
        return $this->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Log stock addition
     */
    public function logStockIn(int $productId, int $quantity, ?string $note = null): bool
    {
        return $this->save([
            'product_id' => $productId,
            'type' => 'in',
            'quantity' => abs($quantity),
            'note' => $note,
        ]);
    }

    /**
     * Log stock reduction
     */
    public function logStockOut(int $productId, int $quantity, ?string $note = null): bool
    {
        return $this->save([
            'product_id' => $productId,
            'type' => 'out',
            'quantity' => abs($quantity),
            'note' => $note,
        ]);
    }

    /**
     * Log stock adjustment
     */
    public function logAdjustment(int $productId, int $quantity, ?string $note = null): bool
    {
        return $this->save([
            'product_id' => $productId,
            'type' => 'adjustment',
            'quantity' => $quantity, // Can be positive or negative
            'note' => $note,
        ]);
    }
}
