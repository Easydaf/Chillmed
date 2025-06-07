<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql; // Import RawSql

class CreateArticlesTable extends Migration
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
            'title' => [ // Changed from 'quote_text'
                'type'        => 'VARCHAR', // VARCHAR is usually sufficient for titles
                'constraint'  => '255',
                'null'        => false,
            ],
            'image' => [ // Nama file gambar artikel
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true, // Boleh kosong jika tidak ada gambar
            ],
            'content' => [ // Changed from 'author', now for the article's body
                'type'        => 'TEXT', // Use TEXT for the main content of the article
                'null'        => false,
            ],
            'author' => [ // Optional: author of the article (retained for clarity)
                'type'        => 'VARCHAR',
                'constraint'  => '100',
                'null'        => true, // Can be null if no author is specified
            ],
            'created_at' => [
                'type'        => 'DATETIME',
                'null'        => false,
                'default'     => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'        => 'DATETIME',
                'null'        => false,
                'default'     => new RawSql('CURRENT_TIMESTAMP'),
                'on_update'   => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true); // Primary Key
        $this->forge->createTable('articles'); // Create a table named 'articles'
    }

    public function down()
    {
        $this->forge->dropTable('articles'); // Drop the 'articles' table on rollback
    }
}