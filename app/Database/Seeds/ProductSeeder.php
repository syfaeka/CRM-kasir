<?php

namespace App\Database\Seeds;

use App\Entities\Product;
use App\Models\ProductModel;
use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productModel = model(ProductModel::class);

        $products = [
            // Coffee Category
            [
                'sku' => 'KOPI-ESP-001',
                'name' => 'Espresso',
                'description' => 'Rich and bold single shot espresso',
                'category' => 'Coffee',
                'price' => 18000,
                'stock' => 100,
            ],
            [
                'sku' => 'KOPI-AMR-001',
                'name' => 'Americano',
                'description' => 'Espresso with hot water',
                'category' => 'Coffee',
                'price' => 22000,
                'stock' => 100,
            ],
            [
                'sku' => 'KOPI-LAT-001',
                'name' => 'Caffe Latte',
                'description' => 'Espresso with steamed milk',
                'category' => 'Coffee',
                'price' => 28000,
                'stock' => 80,
            ],
            [
                'sku' => 'KOPI-CAP-001',
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk foam',
                'category' => 'Coffee',
                'price' => 28000,
                'stock' => 80,
            ],
            [
                'sku' => 'KOPI-MOC-001',
                'name' => 'Mocha',
                'description' => 'Espresso with chocolate and steamed milk',
                'category' => 'Coffee',
                'price' => 32000,
                'stock' => 60,
            ],
            [
                'sku' => 'KOPI-ICE-001',
                'name' => 'Iced Coffee',
                'description' => 'Cold brewed coffee served over ice',
                'category' => 'Coffee',
                'price' => 25000,
                'stock' => 90,
            ],
            [
                'sku' => 'KOPI-FRP-001',
                'name' => 'Frappuccino',
                'description' => 'Blended iced coffee with cream',
                'category' => 'Coffee',
                'price' => 35000,
                'stock' => 50,
            ],
            // Non-Coffee Category
            [
                'sku' => 'TEH-GRN-001',
                'name' => 'Green Tea Latte',
                'description' => 'Matcha green tea with steamed milk',
                'category' => 'Non-Coffee',
                'price' => 30000,
                'stock' => 40,
            ],
            [
                'sku' => 'CHO-HOT-001',
                'name' => 'Hot Chocolate',
                'description' => 'Rich chocolate drink with steamed milk',
                'category' => 'Non-Coffee',
                'price' => 28000,
                'stock' => 45,
            ],
            [
                'sku' => 'JUS-ORA-001',
                'name' => 'Fresh Orange Juice',
                'description' => 'Freshly squeezed orange juice',
                'category' => 'Non-Coffee',
                'price' => 25000,
                'stock' => 30,
            ],
            // Snack Category
            [
                'sku' => 'SNK-CRS-001',
                'name' => 'Butter Croissant',
                'description' => 'Flaky buttery croissant',
                'category' => 'Snack',
                'price' => 25000,
                'stock' => 30,
            ],
            [
                'sku' => 'SNK-CKE-001',
                'name' => 'Chocolate Chip Cookie',
                'description' => 'Warm chocolate chip cookie',
                'category' => 'Snack',
                'price' => 15000,
                'stock' => 50,
            ],
            [
                'sku' => 'SNK-MUF-001',
                'name' => 'Blueberry Muffin',
                'description' => 'Fresh baked blueberry muffin',
                'category' => 'Snack',
                'price' => 20000,
                'stock' => 25,
            ],
            // Food Category
            [
                'sku' => 'FOD-SDW-001',
                'name' => 'Chicken Sandwich',
                'description' => 'Grilled chicken sandwich with vegetables',
                'category' => 'Food',
                'price' => 45000,
                'stock' => 20,
            ],
            [
                'sku' => 'FOD-PAS-001',
                'name' => 'Pasta Carbonara',
                'description' => 'Creamy pasta with bacon and parmesan',
                'category' => 'Food',
                'price' => 55000,
                'stock' => 15,
            ],
        ];

        foreach ($products as $productData) {
            $product = new Product($productData);
            $productModel->insert($product);
        }

        echo "Seeded " . count($products) . " products with categories\n";
    }
}
