<?php
namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();

        // 1. Ambil data POST dan siapkan untuk insert
        $userData = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user' // Secara eksplisit set role 'user' untuk pendaftaran
        ];

        // DD 1: Cek data yang akan di-insert
        // Ini akan menghentikan eksekusi dan menampilkan $userData
        // Jika ini tidak muncul, berarti Controller tidak di-hit
        // dd($userData);

        // 2. Lakukan insert dan cek hasilnya
        $insertResult = $userModel->insert($userData);

        // DD 2: Cek hasil dari insert
        // Ini akan muncul setelah DD1 (jika DD1 di-komentar),
        // dan akan menampilkan true/false atau ID jika sukses
        // dd($insertResult);

        if ($insertResult) {
            // Jika berhasil insert, redirect ke halaman login
            return redirect()->to('/')->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            // Jika insert gagal, ambil error dari model
            $errors = $userModel->errors(); // Mengambil error validasi jika ada
            
            // DD 3: Cek error dari model jika insert gagal
            // dd($errors);

            $errorMessage = 'Registrasi gagal. ';
            if (!empty($errors)) {
                $errorMessage .= implode(' ', $errors);
            } else {
                $errorMessage .= 'Terjadi kesalahan tidak terduga pada database.';
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        // DD 4: Cek data user dari DB dan hasil password_verify
        // dd(['user_from_db' => $user, 'password_from_form' => $password, 'password_verify_result' => ($user ? password_verify($password, $user['password']) : 'User not found')]);

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'isLoggedIn' => true,
                'user' => $user // Simpan seluruh data user di session, termasuk 'role'
            ]);
            return redirect()->to('/dashboard'); // Selalu redirect ke dashboard user
        } else {
            return redirect()->to('/')->with('error', 'Email atau password salah!.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}