<?php


namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class OrderItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_item_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
            ]
        ]);

        // Primary Key
        $this->forge->addKey('order_item_id', true);
        
        // Foreign Keys
        $this->forge->addForeignKey('order_id', 'orders', 'order_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'product_id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}