<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['in', 'out', 'adjustment'],
                'default' => 'in',
            ],
            'quantity' => [
                'type' => 'INT',
                'comment' => 'Positive for in/adjustment increase, negative for out/adjustment decrease',
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addKey('type');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stock_logs');
    }

    public function down(): void
    {
        $this->forge->dropTable('stock_logs');
    }
}
