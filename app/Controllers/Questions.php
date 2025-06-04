<?php

namespace App\Controllers;

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
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorder', 'insomnia'];
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/questions');
        }

        return view('questions/' . $kategori); // e.g., lanjutan/anxiety.php
    }

    // Proses hasil jawaban dari form questions
    public function submit($kategori)
    {
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorder', 'insomnia'];
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/questions');
        }

        $total = 0;
        for ($i = 1; $i <= 9; $i++) {
            $total += (int)$this->request->getPost("q$i");
        }

        // Kirim total skor ke view untuk dianalisis
        return view('questions/' . $kategori, ['score' => $total]);
    }
}
