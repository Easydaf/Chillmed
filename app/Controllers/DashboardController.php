<?php
namespace App\Controllers;

use App\Models\QuoteModel; // WAJIB: Import QuoteModel

class DashboardController extends BaseController
{
    public function index()
    {
        $quoteModel = new QuoteModel();
        $quotes = $quoteModel->select('quote_text')->findAll();

        $quoteTexts = array_column($quotes, 'quote_text');

        $data = [
            'quotesJson' => json_encode($quoteTexts), 
        ];

        return view('dashboard', $data);
    }
}