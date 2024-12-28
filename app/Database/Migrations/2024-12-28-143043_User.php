<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class User extends Migration
{
    public function up()
    {
        //defining the fields 
       $this->forge->addField([
        'user_id'=>[
            'type'=> 'INT',
            'auto_increment'=> true,
            'unsigned' => true
        ],
        'email' => [
             'type'=> 'VARCHAR',
             'unique'     => true,
             'constraint' => '254'
        ],
        'password' => [
             'type'=> 'VARCHAR',
             'constraint' => '254'
        ],
        'created_at' => [
             'type'    => 'TIMESTAMP',
             'default' => new RawSql('CURRENT_TIMESTAMP'),
        ]
       ]);

       //primary key
       $this->forge->addKey('user_id', true);
       $this->forge->createTable('users',true);


    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
