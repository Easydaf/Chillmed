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

        $userData = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user'
        ];

        $insertResult = $userModel->insert($userData);

        if ($insertResult) {
            return redirect()->to('/')->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            $errors = $userModel->errors();
            $errorMessage = 'Registrasi gagal. ';
            if (!empty($errors)) {
                $errorMessage .= implode(' ', $errors);
            } else {
                $errorMessage .= 'Terjadi kesalahan tidak terduga saat menyimpan data.';
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

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'isLoggedIn' => true,
                'user' => $user
            ]);
            return redirect()->to('/dashboard')->with('success', 'Login berhasil!'); // Login Success Message (Optional)
        } else {
            return redirect()->to('/')->with('error', 'Email atau password salah!.');
        }
    }

    // UBAH FUNGSI LOGOUT INI
    public function logout()
    {
        session()->destroy();
        // Redirect ke halaman login dengan pesan sukses logout
        return redirect()->to('/')->with('success', 'Anda telah berhasil logout.');
    }
}