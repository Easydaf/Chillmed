<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetTokenToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true, // Bisa NULL jika tidak ada token
                'after'      => 'password', // Opsional: letakkan setelah kolom password
            ],
            'reset_expires_at' => [
                'type'       => 'DATETIME',
                'null'       => true, // Bisa NULL jika tidak ada token
                'after'      => 'reset_token', // Opsional: letakkan setelah kolom reset_token
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // Untuk rollback migrasi
        $this->forge->dropColumn('users', ['reset_token', 'reset_expires_at']);
    }
}
