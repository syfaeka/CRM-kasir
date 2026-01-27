<?php

namespace App\Models;

use App\Entities\Customer;
use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Customer::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'phone',
        'email',
        'points',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'phone' => 'required|min_length[10]|max_length[20]|is_unique[customers.phone,id,{id}]',
        'email' => 'permit_empty|valid_email',
    ];

    protected $validationMessages = [
        'phone' => [
            'is_unique' => 'This phone number is already registered.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Find customer by phone
     */
    public function findByPhone(string $phone): ?Customer
    {
        return $this->where('phone', $phone)->first();
    }

    /**
     * Get customer transactions (hasMany relationship)
     */
    public function getTransactions(int $customerId): array
    {
        return model(TransactionModel::class)
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Add points to customer
     */
    public function addPoints(int $customerId, int $points): bool
    {
        $customer = $this->find($customerId);
        if (!$customer) {
            return false;
        }

        $customer->addPoints($points);
        return $this->save($customer);
    }

    /**
     * Search customers by name or phone
     */
    public function search(string $keyword, int $limit = 10): array
    {
        return $this->like('name', $keyword)
            ->orLike('phone', $keyword)
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get top customers by points
     */
    public function getTopCustomers(int $limit = 10): array
    {
        return $this->orderBy('points', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
