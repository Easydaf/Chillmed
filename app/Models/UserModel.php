<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'email', 'password', 'role'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // PERBAIKAN PENTING DI BAWAH INI
    protected $updatedField  = 'updated_at'; // Pastikan ini 'updated_at', bukan string kosong!

    // Validation (optional, but good practice)
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role'     => 'required|in_list[admin,user]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
