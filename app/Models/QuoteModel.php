<?php

namespace App\Models;

use CodeIgniter\Model;

class QuoteModel extends Model
{
    protected $table      = 'quotes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Tidak menggunakan soft delete

    protected $allowedFields = ['quote_text', 'author']; // Kolom yang bisa diisi

    // Dates
    protected $useTimestamps = true; // Menggunakan created_at dan updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'quote_text' => 'required|min_length[5]|max_length[500]', // Validasi teks quote
        'author'     => 'permit_empty|max_length[100]',            // Penulis opsional
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}