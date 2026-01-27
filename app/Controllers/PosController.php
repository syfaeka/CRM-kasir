<?php

namespace App\Controllers;

use App\Entities\Customer;
use App\Entities\Transaction;
use App\Entities\TransactionDetail;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class PosController extends BaseController
{
    use ResponseTrait;

    protected ProductModel $productModel;
    protected CustomerModel $customerModel;
    protected TransactionModel $transactionModel;
    protected TransactionDetailModel $transactionDetailModel;

    public function __construct()
    {
        $this->productModel = model(ProductModel::class);
        $this->customerModel = model(CustomerModel::class);
        $this->transactionModel = model(TransactionModel::class);
        $this->transactionDetailModel = model(TransactionDetailModel::class);
    }

    /**
     * Render the POS Interface
     * 
     * GET /pos
     */
    public function index()
    {
        $data = [
            'customers' => $this->customerModel->orderBy('name', 'ASC')->findAll(),
        ];
        return view('pos/index', $data);
    }


    /**
     * Search products by name or SKU
     * 
     * GET /api/pos/products?q={keyword}
     * 
     * @return ResponseInterface
     */
    public function searchProduct(): ResponseInterface
    {
        $keyword = $this->request->getGet('q') ?? '';
        // Ubah limit jadi agak banyak biar produk tampil semua di awal
        $limit = (int) ($this->request->getGet('limit') ?? 100); 

        // ✅ LOGIKA BARU:
        if (strlen($keyword) < 1) {
            // Kalau tidak ada keyword, ambil SEMUA data (pakai findAll bawaan CI4)
            $products = $this->productModel->orderBy('id', 'DESC')->findAll($limit);
        } else {
            // Kalau ada keyword, baru pakai fungsi search custom
            // (Pastikan method 'search' ada di ProductModel, kalau error ganti jadi like)
            if (method_exists($this->productModel, 'search')) {
                $products = $this->productModel->search($keyword, $limit);
            } else {
                // Fallback manual kalau method search belum dibuat di Model
                $products = $this->productModel->like('name', $keyword)
                                             ->orLike('sku', $keyword)
                                             ->findAll($limit);
            }
        }

        // Format response (Sama seperti sebelumnya)
        $data = array_map(function ($product) {
            return [
                'id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'description' => $product->description,
                'category' => $product->category ?? 'Uncategorized',
                'price' => (float) $product->price,
                'stock' => (int) $product->stock,
                // Pastikan method isInStock ada di Entity, kalau error hapus baris ini
                'in_stock' => $product->stock > 0, 
            ];
        }, $products);

        return $this->respond([
            'success' => true,
            'data' => $data,
            'total' => count($data),
        ]);
    }

    /**
     * Process checkout
     * 
     * POST /api/pos/checkout
     * 
     * Request body:
     * {
     *   "customer_id": 1,           // optional
     *   "user_id": 1,               // cashier id (required)
     *   "items": [
     *     {"product_id": 1, "quantity": 2},
     *     {"product_id": 3, "quantity": 1}
     *   ]
     * }
     * 
     * @return ResponseInterface
     */
    public function checkout(): ResponseInterface
    {
        $json = $this->request->getJSON(true);

        // Validate request
        $validation = \Config\Services::validation();
        $validation->setRules([
            'user_id' => 'required|integer',
            'items' => 'required',
        ]);

        if (!$validation->run($json)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        $userId = (int) $json['user_id'];
        $customerId = isset($json['customer_id']) ? (int) $json['customer_id'] : null;
        $items = $json['items'];

        // New payment fields
        $paymentMethod = $json['payment_method'] ?? 'cash';
        $cashReceived = isset($json['cash_received']) ? (float) $json['cash_received'] : null;
        $changeAmount = isset($json['change_amount']) ? (float) $json['change_amount'] : null;

        // Validate items
        if (empty($items) || !is_array($items)) {
            return $this->failValidationErrors(['items' => 'Items must be a non-empty array.']);
        }

        // Validate each item
        foreach ($items as $index => $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                return $this->failValidationErrors([
                    "items.{$index}" => 'Each item must have product_id and quantity.',
                ]);
            }

            if ((int) $item['quantity'] <= 0) {
                return $this->failValidationErrors([
                    "items.{$index}.quantity" => 'Quantity must be greater than 0.',
                ]);
            }
        }

        // Validate customer exists if provided
        if ($customerId !== null) {
            $customer = $this->customerModel->find($customerId);
            if (!$customer) {
                return $this->failNotFound('Customer not found.');
            }
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $totalAmount = 0;
            $transactionDetails = [];

            // Process each item
            foreach ($items as $item) {
                $productId = (int) $item['product_id'];
                $quantity = (int) $item['quantity'];

                // Get product
                $product = $this->productModel->find($productId);
                if (!$product) {
                    $db->transRollback();
                    return $this->failNotFound("Product with ID {$productId} not found.");
                }

                // Check stock
                if (!$product->hasStock($quantity)) {
                    $db->transRollback();
                    return $this->fail([
                        'error' => 'insufficient_stock',
                        'message' => "Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$quantity}",
                    ], 400);
                }

                // Deduct stock
                $product->deductStock($quantity);
                if (!$this->productModel->save($product)) {
                    $db->transRollback();
                    return $this->failServerError('Failed to update product stock.');
                }

                // Calculate subtotal
                $unitPrice = (float) $product->price;
                $subtotal = $unitPrice * $quantity;
                $totalAmount += $subtotal;

                // Prepare transaction detail
                $transactionDetails[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'product' => [
                        'sku' => $product->sku,
                        'name' => $product->name,
                    ],
                ];
            }

            // Calculate loyalty points (1 point per 10,000 IDR)
            $pointsEarned = Customer::calculatePointsFromAmount($totalAmount);

            // Calculate tax (11%)
            $subtotal = $totalAmount;
            $tax = round($subtotal * 0.11, 2);
            $grandTotal = $subtotal + $tax;

            // Create transaction with new payment fields
            $transaction = new Transaction([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'customer_id' => $customerId,
                'user_id' => $userId,
                'total_amount' => $grandTotal,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'payment_method' => $paymentMethod,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
                'points_earned' => $pointsEarned,
            ]);

            if (!$this->transactionModel->save($transaction)) {
                $db->transRollback();
                return $this->failServerError('Failed to create transaction.');
            }

            $transactionId = $this->transactionModel->getInsertID();

            // Create transaction details
            foreach ($transactionDetails as &$detail) {
                $transactionDetail = new TransactionDetail([
                    'transaction_id' => $transactionId,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $detail['subtotal'],
                ]);

                if (!$this->transactionDetailModel->save($transactionDetail)) {
                    $db->transRollback();
                    return $this->failServerError('Failed to create transaction details.');
                }

                $detail['id'] = $this->transactionDetailModel->getInsertID();
            }

            // Add loyalty points to customer
            if ($customerId !== null && $pointsEarned > 0) {
                $customer = $this->customerModel->find($customerId);
                $customer->addPoints($pointsEarned);
                if (!$this->customerModel->save($customer)) {
                    $db->transRollback();
                    return $this->failServerError('Failed to update customer points.');
                }
            }

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->failServerError('Transaction failed. Please try again.');
            }

            // Get the saved transaction
            $savedTransaction = $this->transactionModel->find($transactionId);

            // Prepare response
            $response = [
                'success' => true,
                'message' => 'Checkout completed successfully.',
                'data' => [
                    'transaction' => [
                        'id' => $transactionId,
                        'invoice_number' => $savedTransaction->invoice_number,
                        'subtotal' => (float) $savedTransaction->subtotal,
                        'tax' => (float) $savedTransaction->tax,
                        'total_amount' => (float) $savedTransaction->total_amount,
                        'payment_method' => $savedTransaction->payment_method,
                        'cash_received' => $savedTransaction->cash_received ? (float) $savedTransaction->cash_received : null,
                        'change_amount' => $savedTransaction->change_amount ? (float) $savedTransaction->change_amount : null,
                        'points_earned' => (int) $savedTransaction->points_earned,
                        'created_at' => $savedTransaction->created_at,
                    ],
                    'customer' => $customerId ? [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'total_points' => (int) $customer->points,
                    ] : null,
                    'items' => array_map(function ($detail) {
                        return [
                            'id' => $detail['id'],
                            'product_id' => $detail['product_id'],
                            'sku' => $detail['product']['sku'],
                            'name' => $detail['product']['name'],
                            'quantity' => $detail['quantity'],
                            'unit_price' => $detail['unit_price'],
                            'subtotal' => $detail['subtotal'],
                        ];
                    }, $transactionDetails),
                ],
            ];

            return $this->respondCreated($response);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Checkout error: ' . $e->getMessage());
            return $this->failServerError('An unexpected error occurred during checkout.');
        }
    }

    /**
     * Get product by ID
     * 
     * GET /api/pos/products/{id}
     * 
     * @param int $id
     * @return ResponseInterface
     */
    public function getProduct(int $id): ResponseInterface
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return $this->failNotFound('Product not found.');
        }

        return $this->respond([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (float) $product->price,
                'stock' => (int) $product->stock,
                'in_stock' => $product->isInStock(),
            ],
        ]);
    }

    /**
     * Get transaction by ID or invoice number
     * 
     * GET /api/pos/transactions/{id}
     * 
     * @param string $id
     * @return ResponseInterface
     */
    public function getTransaction(string $id): ResponseInterface
    {
        // Check if it's an invoice number or ID
        if (str_starts_with($id, 'INV-')) {
            $transaction = $this->transactionModel->findByInvoice($id);
        } else {
            $transaction = $this->transactionModel->find((int) $id);
        }

        if (!$transaction) {
            return $this->failNotFound('Transaction not found.');
        }

        // Get full transaction details
        $data = $this->transactionModel->getWithDetails($transaction->id);
        $detailsWithProducts = $this->transactionDetailModel->getDetailsWithProducts($transaction->id);

        return $this->respond([
            'success' => true,
            'data' => [
                'transaction' => [
                    'id' => $transaction->id,
                    'invoice_number' => $transaction->invoice_number,
                    'total_amount' => (float) $transaction->total_amount,
                    'points_earned' => (int) $transaction->points_earned,
                    'created_at' => $transaction->created_at,
                ],
                'customer' => $data['customer'] ? [
                    'id' => $data['customer']->id,
                    'name' => $data['customer']->name,
                    'phone' => $data['customer']->phone,
                    'points' => (int) $data['customer']->points,
                ] : null,
                'cashier' => $data['user'] ? [
                    'id' => $data['user']->id,
                    'name' => $data['user']->name,
                ] : null,
                'items' => array_map(function ($item) {
                    return [
                        'id' => $item['detail']->id,
                        'product_id' => $item['detail']->product_id,
                        'sku' => $item['product']->sku,
                        'name' => $item['product']->name,
                        'quantity' => (int) $item['detail']->quantity,
                        'unit_price' => (float) $item['detail']->unit_price,
                        'subtotal' => (float) $item['detail']->subtotal,
                    ];
                }, $detailsWithProducts),
            ],
        ]);
    }
}
