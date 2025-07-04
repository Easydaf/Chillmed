<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Hasil extends BaseController
{

    public function index($kategori = null) // Ubah dari 'anxiety()' menjadi 'index($kategori = null)'
    {
        $session = session();

        // Ambil score
        $score = $session->getFlashdata('kuesioner_score');
        $kategori_dari_sesi = $session->getFlashdata('kuesioner_kategori');

        // supaya tidak bisa langsung ke hasil.php
        if (empty($score) || empty($kategori_dari_sesi) || $kategori_dari_sesi !== $kategori) {
            return redirect()->to('/questions')->with('error', 'Data hasil tidak ditemukan atau tidak valid. Silakan coba kuesioner lagi.');
        }

        // analisis score
        $hasil_pesan = $this->analyzeScore($kategori, $score);

        // hasil score
        return view('layout/questions/hasil', [
            'skor'    => $score,
            'hasil'   => $hasil_pesan,
            'kategori' => $kategori
        ]);
    }


    private function analyzeScore($kategori, $score)
    {
        $pesan = '';

        switch ($kategori) {
            case 'anxiety':
                // Logika untuk kecemasan
                if ($score <= 12) {
                    $pesan = "Tingkat kecemasan rendah. Tetap jaga kesehatan mentalmu! 😊";
                } elseif ($score <= 24) {
                    $pesan = "Tingkat kecemasan sedang. Coba teknik relaksasi atau mindfulness ya.";
                } else {
                    $pesan = "Tingkat kecemasan tinggi. Disarankan untuk berkonsultasi dengan profesional.";
                }
                break;

            case 'depression':
                // Logika untuk depresi (sesuaikan skala Anda)
                if ($score >= 9 && $score <= 18) {
                    $pesan = "Skor Anda menunjukkan indikasi depresi minimal. Tetap jaga kesehatan mentalmu! 😊";
                } elseif ($score >= 19 && $score <= 27) {
                    $pesan = "Skor Anda menunjukkan indikasi depresi ringan. Coba teknik relaksasi, mindfulness, atau berbicara dengan teman dekat.";
                } elseif ($score >= 28 && $score <= 36) {
                    $pesan = "Skor Anda menunjukkan indikasi depresi sedang. Disarankan untuk mencari dukungan dari konselor kampus atau psikolog.";
                } elseif ($score >= 37 && $score <= 45) {
                    $pesan = "Skor Anda menunjukkan indikasi depresi berat. Sangat disarankan untuk segera berkonsultasi dengan profesional kesehatan mental (psikolog/psikiater) untuk evaluasi lebih lanjut dan penanganan yang tepat.";
                } else {
                    $pesan = "Skor tidak valid. Mohon pastikan semua pertanyaan dijawab.";
                }
                break;

            case 'burnout':
                // Logika untuk burnout (sesuaikan skala Anda)
                if ($score <= 15) {
                    $pesan = "Tingkat burnout Anda rendah. Pertahankan keseimbangan hidup dan jangan ragu untuk istirahat.";
                } elseif ($score <= 30) {
                    $pesan = "Anda mungkin mengalami burnout ringan hingga sedang. Perhatikan tanda-tanda kelelahan dan kelola stres dengan baik.";
                } else {
                    $pesan = "Indikasi burnout tinggi. Penting untuk mengatur ulang prioritas, mencari dukungan, dan beristirahat total.";
                }
                break;

            case 'ducksyndrome':
                // Logika untuk duck syndrome (sesuaikan skala Anda)
                if ($score <= 10) {
                    $pesan = "Anda menunjukkan indikasi Duck Syndrome yang rendah. Bagus! Tetap jujur pada perasaan Anda.";
                } elseif ($score <= 20) {
                    $pesan = "Ada indikasi bahwa Anda mungkin sering menyembunyikan perasaan. Tidak apa-apa untuk tidak baik-baik saja.";
                } else {
                    $pesan = "Indikasi Duck Syndrome tinggi. Penting untuk berbagi perasaan Anda dengan orang terpercaya dan mencari dukungan profesional.";
                }
                break;

            case 'eatingdisorders':
                // Logika untuk eating disorders (sesuaikan skala Anda, dan ingat untuk sangat hati-hati di sini)
                if ($score <= 10) {
                    $pesan = "Anda menunjukkan pola makan yang sehat. Pertahankan kebiasaan baik ini!";
                } elseif ($score <= 20) {
                    $pesan = "Ada beberapa kekhawatiran terkait pola makan Anda. Penting untuk selalu memiliki hubungan yang sehat dengan makanan dan tubuh.";
                } else {
                    $pesan = "Terdapat indikasi masalah makan. Sangat penting bagi Anda untuk segera mencari bantuan dari profesional kesehatan yang terlatih dalam gangguan makan, seperti psikolog atau ahli gizi klinis. Mereka bisa memberikan dukungan dan penanganan yang tepat.";
                }
                break;

            case 'insomnia':
                if ($score <= 10) {
                    $pesan = "Pola tidur Anda terlihat baik. Pertahankan kebiasaan tidur yang sehat.";
                } elseif ($score <= 20) {
                    $pesan = "Ada indikasi masalah tidur ringan. Coba perbaiki rutinitas tidur Anda, hindari kafein sebelum tidur.";
                } else {
                    $pesan = "Indikasi insomnia sedang hingga berat. Jika tidak membaik, konsultasi dengan dokter atau ahli tidur sangat disarankan.";
                }
                break;

            default:
                $pesan = "Hasil untuk kategori ini belum tersedia atau kuesioner tidak dikenal.";
                break;
        }
        return $pesan;
    }
}
