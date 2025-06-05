<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql; // <<< PASTIKAN BARIS INI ADA

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
                'null'       => false,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => false, // <<< PASTIKAN INI false
                'default'    => new RawSql('CURRENT_TIMESTAMP'), // <<< PASTIKAN INI new RawSql()
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => false, // <<< PASTIKAN INI false
                'default'    => new RawSql('CURRENT_TIMESTAMP'), // <<< PASTIKAN INI new RawSql()
                'on_update'  => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}