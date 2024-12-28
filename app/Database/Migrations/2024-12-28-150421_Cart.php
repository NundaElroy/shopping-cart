<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Cart extends Migration
{
    public function up()
{
    $this->forge->addField([
        'cart_id' => [
            'type' => 'INT',
            'auto_increment' => true,
            'unsigned' => true
        ],
        'user_id' => [              // Add this field first
            'type' => 'INT',
            'unsigned' => true
        ],
        'created_at' => [
            'type' => 'TIMESTAMP',
            'default' => new RawSql('CURRENT_TIMESTAMP'),
        ]
    ]);

    $this->forge->addKey('cart_id', true); // Make cart_id the primary key
    
    // Add the foreign key relationship
    $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
    // Format: addForeignKey('field', 'reference_table', 'reference_field', 'on_delete', 'on_update')

    $this->forge->createTable('carts');
}

    public function down()
    {
      $this->forge->dropTable('carts');
    }
}
