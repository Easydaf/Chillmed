<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql; // Ini opsional di sini, tapi bagus untuk konsistensi

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        // Tambahkan kolom 'role' ke tabel 'users'
        $fields = [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'user'], // Pilihan role: 'admin' atau 'user'
                'default'    => 'user',           // Nilai default untuk role baru (akan otomatis 'user' jika tidak diset)
                'after'      => 'password',       // Opsional: letakkan setelah kolom 'password'
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // Hapus kolom 'role' jika migration di-rollback
        $this->forge->dropColumn('users', 'role');
    }
}