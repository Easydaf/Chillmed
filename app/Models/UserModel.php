<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    // TAMBAH 'reset_token' dan 'reset_expires_at' ke allowedFields
    protected $allowedFields = ['name', 'email', 'password', 'role', 'reset_token', 'reset_expires_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation (optional, but good practice)
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role'     => 'required|in_list[admin,user]',
        // Anda bisa menambahkan validasi untuk reset_token jika diperlukan, tapi tidak wajib untuk saat ini
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
}