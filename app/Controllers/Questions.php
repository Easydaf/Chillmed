<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Questions extends BaseController
{

    public function index()
    {

        return view('layout/questions/questions');
    }


    public function show($kategori)
    {
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorders', 'insomnia', 'burnout'];
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/questions')->with('error', 'Kategori pertanyaan tidak valid.');
        }

        return view('layout/questions/' . $kategori);
    }


    public function submit($kategori)
    {
        $allowed = ['anxiety', 'depression', 'ducksyndrome', 'eatingdisorders', 'insomnia', 'burnout'];
        if (!in_array($kategori, $allowed)) {
            return redirect()->to('/hasil')->with('error', 'Kategori pertanyaan tidak valid.');
        }

        $total = 0;
        for ($i = 1; $i <= 9; $i++) {
            $score = $this->request->getPost("q$i");
            if ($score !== null && is_numeric($score)) {
                $total += (int)$score;
            } else {
                return redirect()->back()->withInput()->with('error', 'Semua pertanyaan harus dijawab dengan benar.');
            }
        }

        $session = session();
        $session->setFlashdata('kuesioner_score', $total);    // Simpan skor ke flashdata
        $session->setFlashdata('kuesioner_kategori', $kategori); // Simpan kategori ke flashdata

        // Redirect ke HasilController, yang akan menangani tampilan hasil
        return redirect()->to(base_url('hasil/' . $kategori));
        // ------------------------------------------------------------------
    }

}
    