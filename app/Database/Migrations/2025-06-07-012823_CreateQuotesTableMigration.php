<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql; // Import RawSql

class CreateQuotesTableMigration extends Migration
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
            'quote_text' => [
                'type'        => 'TEXT', // Using TEXT for potentially long quote text
                'null'        => false,
            ],
            'author' => [ // Optional: who said this quote
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
        $this->forge->createTable('quotes'); // Create a table named 'quotes'
    }

    public function down()
    {
        $this->forge->dropTable('quotes'); // Drop the 'quotes' table on rollback
    }
}