<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        return view('home'); // halaman utama
    }

    public function anxiety()
    {
        return view('anxiety'); // halaman form anxiety
    }

    public function depression()
    {
        return view('depression'); // form depresi
    }

    public function ducksyndrom()
    {
        return view('ducksyndrom'); // duck syndrome
    }

    public function eatingdisorder()
    {
        return view('eatingdisorder'); // gangguan makan
    }

    public function insomnia()
    {
        return view('insomnia'); // gangguan tidur
    }
    
}
