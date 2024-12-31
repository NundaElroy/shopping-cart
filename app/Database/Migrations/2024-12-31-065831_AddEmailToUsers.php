<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;

class AddEmailToUsers extends Migration
{
     /**
     * @var string[]
     */
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
       parent::__construct($forge);
        /** @var \Config\Auth $authConfig */
        $authConfig   = config('Auth');
        $this->tables = $authConfig->tables;
    }
    public function up()
    {
        $fields = [
            'email' => ['type' => 'VARCHAR', 'constraint' => '254', 'unique' => true],
        ];

       
        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'email',
        ];
        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
