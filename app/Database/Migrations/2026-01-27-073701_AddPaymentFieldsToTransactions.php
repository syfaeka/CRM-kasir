<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToTransactions extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('transactions', [
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'after' => 'total_amount',
            ],
            'tax' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'after' => 'subtotal',
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'qris'],
                'default' => 'cash',
                'after' => 'tax',
            ],
            'cash_received' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'payment_method',
            ],
            'change_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'cash_received',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('transactions', ['subtotal', 'tax', 'payment_method', 'cash_received', 'change_amount']);
    }
}
