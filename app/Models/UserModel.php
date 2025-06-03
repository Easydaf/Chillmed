<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';         // ✅ Pastikan ini 'users' (jamak)
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'email', 'password'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';           // Kosong karena tidak ada kolom updated_at
}