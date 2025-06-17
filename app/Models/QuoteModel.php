<?php

namespace App\Models;

use CodeIgniter\Model;

class QuoteModel extends Model
{
    protected $table      = 'quotes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // TAMBAH 'user_id' ke allowedFields
    protected $allowedFields = ['user_id', 'quote_text', 'author'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id'    => 'required|is_natural_no_zero', // Pastikan user_id ada dan positif
        'quote_text' => 'required|min_length[5]|max_length[500]',
        'author'     => 'permit_empty|max_length[100]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}