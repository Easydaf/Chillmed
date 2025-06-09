<?php
namespace App\Controllers;

use App\Models\QuoteModel; // WAJIB: Import QuoteModel

class DashboardController extends BaseController
{
    public function index()
    {
        $quoteModel = new QuoteModel();
        // Ambil semua quotes dari database, hanya kolom 'quote_text' yang diperlukan
        $quotes = $quoteModel->select('quote_text')->findAll();

        // Konversi array quotes menjadi array string teks quote saja untuk JS
        $quoteTexts = array_column($quotes, 'quote_text');

        $data = [
            'quotesJson' => json_encode($quoteTexts), // Kirim sebagai JSON string ke view
        ];

        return view('dashboard', $data);
    }
}