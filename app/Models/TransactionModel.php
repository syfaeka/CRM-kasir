<?php

namespace App\Models;

use App\Entities\Transaction;
use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Transaction::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invoice_number',
        'customer_id',
        'user_id',
        'total_amount',
        'subtotal',
        'tax',
        'payment_method',
        'cash_received',
        'change_amount',
        'points_earned',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'invoice_number' => 'required|is_unique[transactions.invoice_number,id,{id}]',
        'user_id' => 'required|integer',
        'total_amount' => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $skipValidation = false;

    /**
     * Find transaction by invoice number
     */
    public function findByInvoice(string $invoiceNumber): ?Transaction
    {
        return $this->where('invoice_number', $invoiceNumber)->first();
    }

    /**
     * Get transaction customer (belongsTo relationship)
     */
    public function getCustomer(int $transactionId): ?object
    {
        $transaction = $this->find($transactionId);
        if (!$transaction || !$transaction->hasCustomer()) {
            return null;
        }

        return model(CustomerModel::class)->find($transaction->customer_id);
    }

    /**
     * Get transaction cashier/user (belongsTo relationship)
     */
    public function getUser(int $transactionId): ?object
    {
        $transaction = $this->find($transactionId);
        if (!$transaction) {
            return null;
        }

        return model(UserModel::class)->find($transaction->user_id);
    }

    /**
     * Get transaction details (hasMany relationship)
     */
    public function getDetails(int $transactionId): array
    {
        return model(TransactionDetailModel::class)
            ->where('transaction_id', $transactionId)
            ->findAll();
    }

    /**
     * Get transaction with all details and relationships loaded
     */
    public function getWithDetails(int $transactionId): ?array
    {
        $transaction = $this->find($transactionId);
        if (!$transaction) {
            return null;
        }

        $details = $this->getDetails($transactionId);
        $customer = $this->getCustomer($transactionId);
        $user = $this->getUser($transactionId);

        return [
            'transaction' => $transaction,
            'details' => $details,
            'customer' => $customer,
            'user' => $user,
        ];
    }

    /**
     * Get transactions by customer
     */
    public function getByCustomer(int $customerId, int $limit = 50): array
    {
        return $this->where('customer_id', $customerId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get transactions by date range
     */
    public function getByDateRange(string $startDate, string $endDate): array
    {
        return $this->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get today's transactions
     */
    public function getToday(): array
    {
        $today = date('Y-m-d');
        return $this->where('DATE(created_at)', $today)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get total sales amount for a date range
     */
    public function getTotalSales(string $startDate, string $endDate): float
    {
        $result = $this->selectSum('total_amount')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->first();

        return (float) ($result->total_amount ?? 0);
    }
}
