<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Questions extends BaseController
{
    // Menampilkan halaman menu awal (berisi 5 gangguan)
    public function index()
    {
        return view('questions'); // yang isinya seperti gambar UI kamu
    }

    // Menampilkan halaman form questions berdasarkan kategori
    public function show($kategori)
    {
        // Pastikan kategori yang diminta ada dalam daftar yang diizinkan
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorder', 'insomnia', 'burnout']; // Tambahkan 'burnout'
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/questions')->with('error', 'Kategori pertanyaan tidak valid.');
        }

        // Memuat view dari folder 'layout/questions/'
        return view('layout/questions/' . $kategori); // e.g., view('layout/questions/anxiety.php')
    }

    // Proses hasil jawaban dari form questions
    public function submit($kategori)
    {
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorder', 'insomnia', 'burnout']; // Tambahkan 'burnout'
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/questions')->with('error', 'Kategori pertanyaan tidak valid.');
        }

        $total = 0;
        // Asumsi pertanyaan q1 sampai q9
        for ($i = 1; $i <= 9; $i++) { // Perhatikan indeks dimulai dari 1 jika form menggunakan q1, q2, dst.
            $total += (int)$this->request->getPost("q$i");
        }

        // Anda bisa menyimpan skor ini ke database atau sesi jika diperlukan
        // $session = session();
        // $session->set('last_score_' . $kategori, $total);

        // Arahkan ke halaman hasil yang lebih umum atau tampilkan di sini
        // Contoh: Mengirim skor ke view 'hasil' yang umum, atau view khusus kategori
        // Di sini saya akan mengarahkan ke controller Hasil (jika Anda ingin memisahkannya)
        // atau Anda bisa memproses dan menampilkan hasilnya langsung di sini.

        // Jika Anda ingin memproses hasil di controller Hasil, Anda bisa redirect ke sana
        // return redirect()->to(base_url('hasil/' . $kategori))->with('score', $total);

        // Atau, jika Anda ingin menampilkan hasil di view yang sama atau view baru di dalam Questions
        // Untuk saat ini, saya akan mengembalikan ke view kategori dengan skor
        // Anda mungkin perlu membuat view 'hasil_kategori.php' atau sejenisnya
        return view('hasil', ['skor' => $total, 'hasil' => $this->analyzeScore($kategori, $total)]);
    }

    // Metode helper untuk menganalisis skor (contoh sederhana)
    private function analyzeScore($kategori, $score)
    {
        $message = "Skor Anda untuk $kategori adalah $score. ";

        // Contoh sederhana, Anda perlu menyesuaikan skala dan pesan untuk setiap kategori
        if ($score <= 12) {
            $message .= "Tingkat gejala rendah. Tetap jaga kesehatan mentalmu! 😊";
        } elseif ($score <= 24) {
            $message .= "Tingkat gejala sedang. Coba teknik relaksasi atau mindfulness ya.";
        } else {
            $message .= "Tingkat gejala tinggi. Disarankan untuk berkonsultasi dengan profesional kesehatan mental.";
        }
        return $message;
    }
}