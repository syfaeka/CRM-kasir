<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToProducts extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('products', [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'description',
            ],
        ]);

        // Add index for category filtering
        $this->db->query('CREATE INDEX idx_products_category ON products(category)');
    }

    public function down(): void
    {
        $this->forge->dropColumn('products', 'category');
    }
}
