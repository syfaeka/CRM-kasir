<?php

namespace App\Database\Seeds;

use App\Entities\Customer;
use App\Entities\Product;
use App\Entities\User;
use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('UserSeeder');
        $this->call('CustomerSeeder');
        $this->call('ProductSeeder');
    }
}
