<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Orders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Paid'],
                'default' => 'Pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
            ]
        ]);

        // Primary Key
        $this->forge->addKey('order_id', true);
        
        // Foreign Keys
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}