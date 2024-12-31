<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CartItems extends Migration
{
    public function up()
    {
       $this->forge->addField([
           'cart_item_id' => [
               'type' => 'INT',
               'auto_increment' => true,
               'unsigned' => true
           ],
           'cart_id' => [
               'type' => 'INT',
               'unsigned' => true,
               'null' => false
           ],
           'product_id' => [
               'type' => 'INT',
               'unsigned' => true,
               'null' => false
           ],
           'quantity' => [
               'type' => 'INT',
               'unsigned' => true,
               'null' => false,
               'default' => 1
           ],
           'created_at' => [
               'type' => 'DATETIME',
               'default' => new RawSql('CURRENT_TIMESTAMP')
           ],
           'updated_at' => [
            'type' => 'TIMESTAMP',
            'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        ]
       ]);
    
       // Primary Key
       $this->forge->addKey('cart_item_id', true);
    
       // Foreign Keys
       $this->forge->addForeignKey('cart_id', 'carts', 'cart_id', 'CASCADE', 'CASCADE');
       $this->forge->addForeignKey('product_id', 'products', 'product_id', 'RESTRICT', 'CASCADE');
    
       $this->forge->createTable('cart_items');
    }
    
    public function down()
    {
       $this->forge->dropTable('cart_items');
    }
}
