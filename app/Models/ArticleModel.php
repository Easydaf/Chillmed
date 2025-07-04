<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table      = 'articles';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // TAMBAH 'user_id' ke allowedFields
    protected $allowedFields = ['user_id', 'title', 'image', 'content', 'author'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero', // Pastikan user_id ada dan positif
        'title'   => 'required|min_length[5]|max_length[255]',
        'image'   => 'permit_empty|max_size[image,1024]|ext_in[image,png,jpg,jpeg,gif]',
        'content' => 'required|min_length[20]',
        'author'  => 'permit_empty|max_length[100]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}