<?php

namespace App\Controllers;

class Hasil extends BaseController
{
    public function anxiety()
    {
        $total = 0;
        for ($i = 0; $i < 9; $i++) {
            $total += intval($this->request->getPost("q$i"));
        }

        // Kategori berdasarkan skor total (skala 1-5)
        if ($total <= 12) {
            $hasil = "Tingkat kecemasan rendah. Tetap jaga kesehatan mentalmu! 😊";
        } elseif ($total <= 24) {
            $hasil = "Tingkat kecemasan sedang. Coba teknik relaksasi atau mindfulness ya.";
        } else {
            $hasil = "Tingkat kecemasan tinggi. Disarankan untuk berkonsultasi dengan profesional.";
        }

        return view('hasil', ['skor' => $total, 'hasil' => $hasil]);
    }
}
