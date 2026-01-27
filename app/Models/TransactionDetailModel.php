<?php

namespace App\Models;

use App\Entities\TransactionDetail;
use CodeIgniter\Model;

class TransactionDetailModel extends Model
{
    protected $table = 'transaction_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = TransactionDetail::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'transaction_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    // No timestamps for detail table
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'transaction_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'unit_price' => 'required|numeric|greater_than_equal_to[0]',
        'subtotal' => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $skipValidation = false;

    /**
     * Get transaction (belongsTo relationship)
     */
    public function getTransaction(int $detailId): ?object
    {
        $detail = $this->find($detailId);
        if (!$detail) {
            return null;
        }

        return model(TransactionModel::class)->find($detail->transaction_id);
    }

    /**
     * Get product (belongsTo relationship)
     */
    public function getProduct(int $detailId): ?object
    {
        $detail = $this->find($detailId);
        if (!$detail) {
            return null;
        }

        return model(ProductModel::class)->find($detail->product_id);
    }

    /**
     * Get all details for a transaction with product info
     */
    public function getDetailsWithProducts(int $transactionId): array
    {
        $details = $this->where('transaction_id', $transactionId)->findAll();
        $productModel = model(ProductModel::class);

        $result = [];
        foreach ($details as $detail) {
            $product = $productModel->find($detail->product_id);
            $result[] = [
                'detail' => $detail,
                'product' => $product,
            ];
        }

        return $result;
    }

    /**
     * Get best selling products
     */
    public function getBestSellers(int $limit = 10): array
    {
        return $this->select('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
