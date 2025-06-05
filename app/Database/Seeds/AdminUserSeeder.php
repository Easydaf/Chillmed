<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'       => 'Admin ChillMed',
            'email'      => 'admin@chillmed.com', // Email admin
            'password'   => password_hash('password123', PASSWORD_DEFAULT), // Hash password
            'role'       => 'admin', // Role admin
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Cek apakah user admin sudah ada berdasarkan email
        $existingAdmin = $this->db->table('users')->where('email', $data['email'])->get()->getRow();

        if (empty($existingAdmin)) {
            // Masukkan data user admin ke tabel 'users'
            $this->db->table('users')->insert($data);
            echo "Admin user '{$data['email']}' created successfully.\n";
        } else {
            echo "Admin user '{$data['email']}' already exists. Skipping.\n";
        }
    }
}