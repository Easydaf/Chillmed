<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\I18n\Time; // Untuk mengelola waktu kedaluwarsa token
use CodeIgniter\Email\Email; // Import Class Email

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
            return redirect()->to('/dashboard')->with('success', 'Login berhasil!');
        } else {
            return redirect()->to('/')->with('error', 'Email atau password salah!.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda telah berhasil logout.');
    }

    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $userModel = new UserModel();
            $email = $this->request->getPost('email');

            $validation = \Config\Services::validation();
            $validation->setRules(['email' => 'required|valid_email']);
            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('error', $validation->getErrors()['email']);
            }

            $user = $userModel->where('email', $email)->first();

            $successMessage = 'Jika email Anda terdaftar, link reset password telah dikirimkan ke email Anda.';

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = Time::now()->addMinutes(30);

                $userModel->update($user['id'], [
                    'reset_token'      => $token,
                    'reset_expires_at' => $expires->toDateTimeString(),
                ]);

                $emailService = \Config\Services::email();
                $resetLink = base_url('reset-password?token=' . $token);

                $emailService->setTo($email);
                $emailService->setSubject('Reset Password Akun ChillMed Anda');
                $emailService->setMessage("Halo {$user['name']},<br><br>Kami menerima permintaan untuk mereset password akun ChillMed Anda.<br>Silakan klik link di bawah ini untuk mereset password Anda:<br><br><a href='{$resetLink}'>{$resetLink}</a><br><br>Link ini akan kadaluarsa dalam 30 menit.<br>Jika Anda tidak meminta reset password ini, abaikan email ini.<br><br>Terima kasih,<br>Tim ChillMed.");

                if ($emailService->send()) {
                    log_message('info', 'Reset password email sent to ' . $email);
                } else {
                    log_message('error', 'Failed to send reset password email to ' . $email . '. Error: ' . $emailService->printDebugger(['headers']));
                }
            }
            return redirect()->to('/')->with('success', $successMessage);

        }
        return view('auth/forgot_password');
    }

    public function resetPassword()
    {
        $token = $this->request->getGet('token');

        $userModel = new UserModel();

        if ($this->request->getMethod() === 'POST') {
            $password = $this->request->getPost('password');
            $passwordConfirm = $this->request->getPost('password_confirm');
            $postedToken = $this->request->getPost('token');

            $validation = \Config\Services::validation();
            $validation->setRules([
                'password'        => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]',
            ]);
            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('error', implode(' ', $validation->getErrors()));
            }

            $user = $userModel->where('reset_token', $postedToken)->first();

            $isTokenValid = false;
            // PERBAIKAN DI SINI: Gunakan perbandingan manual secara konsisten
            if ($user && $user['reset_expires_at'] !== null) {
                $expiresAt = Time::parse($user['reset_expires_at']);
                if ($expiresAt->getTimestamp() > Time::now()->getTimestamp()) { // Gunakan perbandingan timestamp
                    $isTokenValid = true;
                }
            }
            
            if ($user && $isTokenValid) {
                $userModel->update($user['id'], [
                    'password'         => password_hash($password, PASSWORD_DEFAULT),
                    'reset_token'      => null,
                    'reset_expires_at' => null,
                ]);
                return redirect()->to('/')->with('success', 'Password Anda berhasil direset! Silakan login dengan password baru Anda.');
            } else {
                return redirect()->to('/')->with('error', 'Link reset password tidak valid atau sudah kadaluarsa. Silakan coba lagi.');
            }

        }
        // Jika request adalah GET, tampilkan form reset password
        if (empty($token)) {
            return redirect()->to('/')->with('error', 'Token reset password tidak ditemukan.');
        }

        $user = $userModel->where('reset_token', $token)->first();

        $isTokenValid = false;
        // PERBAIKAN DI SINI: Gunakan perbandingan manual secara konsisten
        if ($user && $user['reset_expires_at'] !== null) {
            $expiresAt = Time::parse($user['reset_expires_at']);
            if ($expiresAt->getTimestamp() > Time::now()->getTimestamp()) { // Gunakan perbandingan timestamp
                $isTokenValid = true;
            }
        }
        
        if (!$user || !$isTokenValid) {
            return redirect()->to('/')->with('error', 'Link reset password tidak valid atau sudah kadaluarsa. Silakan coba lagi.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }
}