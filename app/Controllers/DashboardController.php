<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TransactionModel;

class DashboardController extends BaseController
{
    protected ProductModel $productModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->productModel = model(ProductModel::class);
        $this->transactionModel = model(TransactionModel::class);
    }

    /**
     * Show admin dashboard with stats
     */
    public function index()
    {
        // Get today's date range
        $today = date('Y-m-d');
        $startOfDay = $today . ' 00:00:00';
        $endOfDay = $today . ' 23:59:59';

        // Today's transactions
        $todayTransactions = $this->transactionModel
            ->where('created_at >=', $startOfDay)
            ->where('created_at <=', $endOfDay)
            ->findAll();

        // Calculate totals
        $totalSalesToday = array_sum(array_column($todayTransactions, 'total_amount'));
        $totalTransactionsToday = count($todayTransactions);

        // Low stock products (less than 5 items)
        $lowStockProducts = $this->productModel
            ->where('stock <', 5)
            ->orderBy('stock', 'ASC')
            ->findAll();

        // Recent transactions (last 10)
        $recentTransactions = $this->transactionModel
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'totalSalesToday' => $totalSalesToday,
            'totalTransactionsToday' => $totalTransactionsToday,
            'lowStockProducts' => $lowStockProducts,
            'lowStockCount' => count($lowStockProducts),
            'recentTransactions' => $recentTransactions,
        ];

        return view('dashboard/index', $data);
    }
}
