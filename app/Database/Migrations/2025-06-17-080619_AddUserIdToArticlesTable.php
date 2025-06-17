<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToArticlesTable extends Migration
{
    public function up()
    {
        $fields = [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false, // User ID tidak boleh null
                'after'      => 'id', // Opsional: letakkan setelah kolom ID
            ],
        ];
        $this->forge->addColumn('articles', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('articles', 'user_id');
    }
}