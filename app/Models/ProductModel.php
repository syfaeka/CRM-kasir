<?php

namespace App\Models;

use App\Entities\Product;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = Product::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'sku',
        'name',
        'description',
        'category',
        'price',
        'stock',
        'image',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'sku' => 'required|min_length[2]|max_length[50]|is_unique[products.sku,id,{id}]',
        'name' => 'required|min_length[2]|max_length[255]',
        'price' => 'required|numeric|greater_than_equal_to[0]',
        'stock' => 'required|integer|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'sku' => [
            'is_unique' => 'This SKU is already in use.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Find product by SKU
     */
    public function findBySku(string $sku): ?Product
    {
        return $this->where('sku', $sku)->first();
    }

    /**
     * Search products by name or SKU
     */
    public function search(string $keyword, int $limit = 20): array
    {
        return $this->groupStart()
            ->like('name', $keyword)
            ->orLike('sku', $keyword)
            ->groupEnd()
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get products in stock
     */
    public function getInStock(): array
    {
        return $this->where('stock >', 0)->findAll();
    }

    /**
     * Get low stock products (default: less than 10)
     */
    public function getLowStock(int $threshold = 10): array
    {
        return $this->where('stock <', $threshold)
            ->where('stock >', 0)
            ->findAll();
    }

    /**
     * Get out of stock products
     */
    public function getOutOfStock(): array
    {
        return $this->where('stock', 0)->findAll();
    }

    /**
     * Deduct stock for a product
     */
    public function deductStock(int $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        if (!$product || !$product->hasStock($quantity)) {
            return false;
        }

        $product->deductStock($quantity);
        return $this->save($product);
    }

    /**
     * Add stock for a product
     */
    public function addStock(int $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        $product->addStock($quantity);
        return $this->save($product);
    }
}
