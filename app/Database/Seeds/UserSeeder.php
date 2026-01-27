<?php

namespace App\Database\Seeds;

use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $userModel = model(UserModel::class);

        $users = [
            [
                'email' => 'admin@kopikuy.com',
                'password' => 'admin123',
                'name' => 'Administrator',
                'role' => 'admin',
            ],
            [
                'email' => 'cashier1@kopikuy.com',
                'password' => 'cashier123',
                'name' => 'Budi Santoso',
                'role' => 'cashier',
            ],
            [
                'email' => 'cashier2@kopikuy.com',
                'password' => 'cashier123',
                'name' => 'Siti Rahayu',
                'role' => 'cashier',
            ],
        ];

        foreach ($users as $userData) {
            $user = new User($userData);
            // Password will be hashed by the Entity's setPassword method
            $user->setPassword($userData['password']);
            $userModel->insert($user);
        }

        echo "Seeded " . count($users) . " users (1 admin, 2 cashiers)\n";
    }
}
