<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class TransactionController extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->transactionModel = model(TransactionModel::class);
    }

    /**
     * List all transactions with pagination and filters
     */
    public function index()
    {
        $perPage = 20;

        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Build query
        $builder = $this->transactionModel->builder();

        // Apply date range filter if provided
        if ($startDate && $endDate) {
            $builder->where('DATE(created_at) >=', $startDate)
                ->where('DATE(created_at) <=', $endDate);
        } elseif ($startDate) {
            $builder->where('DATE(created_at) >=', $startDate);
        } elseif ($endDate) {
            $builder->where('DATE(created_at) <=', $endDate);
        }

        // Order by latest first
        $builder->orderBy('created_at', 'DESC');

        // Get paginated results
        $transactions = $this->transactionModel
            ->select('transactions.*, customers.name as customer_name, users.username as cashier')
            ->join('customers', 'customers.id = transactions.customer_id', 'left')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->paginate($perPage);

        $data = [
            'transactions' => $transactions,
            'pager' => $this->transactionModel->pager,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        return view('admin/transactions/index', $data);
    }

    /**
     * Show full invoice details
     */
    public function show(int $id)
    {
        $transaction = $this->transactionModel->find($id);

        if (!$transaction) {
            return redirect()->to('/admin/transactions')
                ->with('error', 'Transaction not found.');
        }

        // Get full transaction details with relationships
        $data = $this->transactionModel->getWithDetails($id);

        if (!$data) {
            return redirect()->to('/admin/transactions')
                ->with('error', 'Transaction not found.');
        }

        // Get product details for each transaction detail
        $productModel = model(\App\Models\ProductModel::class);
        foreach ($data['details'] as $detail) {
            $product = $productModel->find($detail->product_id);
            $detail->product = $product;
        }

        return view('admin/transactions/show', $data);
    }

    /**
     * Delete transaction (optional - for admin cleanup)
     */
    public function delete(int $id): ResponseInterface
    {
        $transaction = $this->transactionModel->find($id);

        if (!$transaction) {
            return redirect()->to('/admin/transactions')
                ->with('error', 'Transaction not found.');
        }

        // Note: In production, you might want to prevent deletion of transactions
        // and use soft deletes or archive functionality instead
        if ($this->transactionModel->delete($id)) {
            return redirect()->to('/admin/transactions')
                ->with('success', 'Transaction deleted successfully.');
        }

        return redirect()->to('/admin/transactions')
            ->with('error', 'Failed to delete transaction.');
    }
}
