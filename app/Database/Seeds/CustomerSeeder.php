<?php

namespace App\Database\Seeds;

use App\Entities\Customer;
use App\Models\CustomerModel;
use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customerModel = model(CustomerModel::class);

        $customers = [
            [
                'name' => 'Ahmad Wijaya',
                'phone' => '081234567890',
                'email' => 'ahmad.wijaya@email.com',
                'points' => 150,
            ],
            [
                'name' => 'Dewi Lestari',
                'phone' => '082345678901',
                'email' => 'dewi.lestari@email.com',
                'points' => 280,
            ],
            [
                'name' => 'Rizky Pratama',
                'phone' => '083456789012',
                'email' => 'rizky.pratama@email.com',
                'points' => 75,
            ],
            [
                'name' => 'Maya Sari',
                'phone' => '084567890123',
                'email' => 'maya.sari@email.com',
                'points' => 420,
            ],
            [
                'name' => 'Eko Nugroho',
                'phone' => '085678901234',
                'email' => null,
                'points' => 50,
            ],
        ];

        foreach ($customers as $customerData) {
            $customer = new Customer($customerData);
            $customerModel->insert($customer);
        }

        echo "Seeded " . count($customers) . " customers\n";
    }
}
