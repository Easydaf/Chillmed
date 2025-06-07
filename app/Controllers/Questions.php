<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Questions extends BaseController
{
    // Menampilkan halaman menu awal (berisi daftar kuesioner)
    public function index()
    {
        // Path ini sudah benar sesuai klarifikasi terakhir Anda
        return view('layout/questions/questions');
    }

    // Menampilkan halaman form questions berdasarkan kategori
    public function show($kategori)
    {
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorders', 'insomnia', 'burnout'];
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/questions')->with('error', 'Kategori pertanyaan tidak valid.');
        }

        // Path ini sudah benar
        return view('layout/questions/' . $kategori);
    }

    // Proses hasil jawaban dari form questions
    public function submit($kategori)
    {
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorders', 'insomnia', 'burnout'];
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/hasil')->with('error', 'Kategori pertanyaan tidak valid.');
        }

        $total = 0;
        // Asumsi pertanyaan q1 sampai q9. Pastikan ini sesuai dengan nama input di form HTML Anda.
        for ($i = 1; $i <= 9; $i++) {
            $score = $this->request->getPost("q$i");
            if ($score !== null && is_numeric($score)) {
                $total += (int)$score;
            } else {
                // Jika ada pertanyaan yang tidak dijawab, kembalikan dengan error
                return redirect()->back()->withInput()->with('error', 'Semua pertanyaan harus dijawab dengan benar.');
            }
        }

        // --- INI BAGIAN PENTING UNTUK MENGHUBUNGKAN KE HasilController ---
        $session = session();
        $session->setFlashdata('kuesioner_score', $total);    // Simpan skor ke flashdata
        $session->setFlashdata('kuesioner_kategori', $kategori); // Simpan kategori ke flashdata

        // Redirect ke HasilController, yang akan menangani tampilan hasil
        return redirect()->to(base_url('hasil/' . $kategori)); // Redirect ke URL /hasil/anxiety, /hasil/depression, dst.
        // ------------------------------------------------------------------
    }

    // Metode analyzeScore TIDAK DIGUNAKAN DI SINI jika hasil di Pisah ke HasilController
    // Metode ini hanya dibutuhkan jika Anda ingin menampilkan hasil langsung di QuestionsController
    // Anda bisa menghapus metode ini dari Questions.php atau biarkan saja jika tidak dipanggil
    // private function analyzeScore($kategori, $score)
    // {
    //     // ... logika analyzeScore (akan ada di HasilController) ...
    // }
}
    