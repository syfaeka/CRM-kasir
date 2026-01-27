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
            // Hot Coffee
            [
                'sku' => 'KOPI-ESP-001',
                'name' => 'Espresso',
                'description' => 'Rich and bold single shot espresso',
                'price' => 18000,
                'stock' => 100,
            ],
            [
                'sku' => 'KOPI-AMR-001',
                'name' => 'Americano',
                'description' => 'Espresso with hot water',
                'price' => 22000,
                'stock' => 100,
            ],
            [
                'sku' => 'KOPI-LAT-001',
                'name' => 'Caffe Latte',
                'description' => 'Espresso with steamed milk',
                'price' => 28000,
                'stock' => 80,
            ],
            [
                'sku' => 'KOPI-CAP-001',
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk foam',
                'price' => 28000,
                'stock' => 80,
            ],
            [
                'sku' => 'KOPI-MOC-001',
                'name' => 'Mocha',
                'description' => 'Espresso with chocolate and steamed milk',
                'price' => 32000,
                'stock' => 60,
            ],
            // Iced Coffee
            [
                'sku' => 'KOPI-ICE-001',
                'name' => 'Iced Coffee',
                'description' => 'Cold brewed coffee served over ice',
                'price' => 25000,
                'stock' => 90,
            ],
            [
                'sku' => 'KOPI-FRP-001',
                'name' => 'Frappuccino',
                'description' => 'Blended iced coffee with cream',
                'price' => 35000,
                'stock' => 50,
            ],
            // Non-Coffee
            [
                'sku' => 'TEH-GRN-001',
                'name' => 'Green Tea Latte',
                'description' => 'Matcha green tea with steamed milk',
                'price' => 30000,
                'stock' => 40,
            ],
            [
                'sku' => 'CHO-HOT-001',
                'name' => 'Hot Chocolate',
                'description' => 'Rich chocolate drink with steamed milk',
                'price' => 28000,
                'stock' => 45,
            ],
            // Food
            [
                'sku' => 'SNK-CRS-001',
                'name' => 'Butter Croissant',
                'description' => 'Flaky buttery croissant',
                'price' => 25000,
                'stock' => 30,
            ],
        ];

        foreach ($products as $productData) {
            $product = new Product($productData);
            $productModel->insert($product);
        }

        echo "Seeded " . count($products) . " products\n";
    }
}
