<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Product extends Migration
{
    public function up()
{
   $this->forge->addField([
       'product_id' => [
           'type' => 'INT',
           'auto_increment' => true,
           'unsigned' => true
       ],
       'name' => [
           'type' => 'VARCHAR',
           'constraint' => 255,
           'null' => false
       ],
       'price' => [
           'type' => 'DECIMAL',
           'constraint' => '10,2',
           'null' => false
       ],
       'stock' => [
           'type' => 'INT',
           'unsigned' => true,  // Stock can't be negative
           'null' => false,
           'default' => 0
       ],
       'created_at' => [
           'type' => 'TIMESTAMP',
           'default' => new RawSql('CURRENT_TIMESTAMP')
       ],
       'updated_at' => [
           'type' => 'TIMESTAMP',
           'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
       ]
   ]);

   $this->forge->addKey('product_id', true); // Set as primary key
   $this->forge->createTable('products');
}

public function down()
{
   $this->forge->dropTable('products');
}
}
