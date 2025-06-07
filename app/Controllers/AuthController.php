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
        $userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);
        return redirect()->to('/');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
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
