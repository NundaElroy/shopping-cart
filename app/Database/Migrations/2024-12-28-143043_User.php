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
        'id'=>[
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
        ],
        'updated_at' => [
          'type' => 'TIMESTAMP',
          'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
      ]
       ]);

       //primary key
       $this->forge->addKey('id', true);
       $this->forge->createTable('users',true);


    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
