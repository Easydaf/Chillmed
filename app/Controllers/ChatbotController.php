<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use GuzzleHttp\Client; // Import Guzzle HTTP Client
use GuzzleHttp\Exception\RequestException; // Import untuk menangani exception Guzzle

class ChatbotController extends Controller
{
    /**
     * Metode default untuk menampilkan view chatbot.
     * Biasanya akan me-load file app/Views/chatbot.php
     */
    public function index()
    {
        return view('chatbot');
    }

    /**
     * Metode untuk memproses pesan dari pengguna dan memberikan respons.
     * Menggunakan sistem berbasis aturan dengan manajemen sesi untuk alur percakapan.
     */
    public function message()
    {
        $session = session(); // Mengambil instance sesi
        $input = strtolower($this->request->getPost('message')); // Mengambil pesan pengguna dan mengubah ke huruf kecil
        $response = ''; // Variabel untuk menyimpan respons bot

        // Mengambil 'step' percakapan dari sesi, default 0 jika belum ada
        $step = $session->get('step') ?? 0;

        // Logika percakapan berdasarkan 'step'
        switch ($step) {
            case 0: // Tahap 0: Deteksi keluhan utama atau sapaan awal
                if (str_contains($input, 'halo') || str_contains($input, 'hai') || str_contains($input, 'apa kabar')) {
                    $response = "Halo juga! 😊 Apa yang sedang kamu rasakan hari ini? Ceritakan saja, aku di sini untuk mendengarkan.";
                    // Tetap di step 0 agar bisa mendeteksi keluhan utama di balasan selanjutnya
                } elseif (str_contains($input, 'sedih') || str_contains($input, 'depresi') || str_contains($input, 'putus asa') || str_contains($input, 'tidak semangat')) {
                    $response = "Aku turut prihatin kamu merasakan itu. Sudah berapa lama perasaan sedih ini muncul? Apakah ada hal lain yang membuatmu kehilangan minat?";
                    $session->set('step', 10); // Lanjut ke alur Depresi
                } elseif (str_contains($input, 'cemas') || str_contains($input, 'gelisah') || str_contains($input, 'panik') || str_contains($input, 'khawatir')) {
                    $response = "Aku mengerti perasaan cemas itu tidak nyaman. Kecemasan ini sering muncul kapan? Apakah ada pemicu tertentu?";
                    $session->set('step', 20); // Lanjut ke alur Kecemasan
                } elseif (str_contains($input, 'stress') || str_contains($input, 'lelah') || str_contains($input, 'burnout') || str_contains($input, 'sulit fokus') || str_contains($input, 'tugas menumpuk')) {
                    $response = "Aku paham rasanya lelah dan sulit fokus. Seberapa sering kamu merasakan ini? Apakah ini terkait dengan tekanan kuliah atau tugas?";
                    $session->set('step', 30); // Lanjut ke alur Burnout/Stres Akademik
                } elseif (str_contains($input, 'tidur') || str_contains($input, 'insomnia') || str_contains($input, 'begadang')) {
                    $response = "Sulit tidur memang tidak nyaman. Sudah berapa lama kamu mengalami masalah tidur ini? Apakah kamu kesulitan memulai tidur atau sering terbangun di malam hari?";
                    $session->set('step', 40); // Lanjut ke alur Masalah Tidur
                } else {
                    // Jika tidak ada kata kunci yang cocok di rule-based, fallback ke Gemini API
                    $response = $this->getGeminiResponse($input);
                    $session->remove('step'); // Reset step setelah menggunakan Gemini
                }
                break;

            // --- Alur Depresi (Step 10-19) ---
            case 10: // Setelah deteksi awal sedih/depresi
                // Pertanyaan: "Sudah berapa lama perasaan sedih ini muncul? Apakah ada hal lain yang membuatmu kehilangan minat?"
                if (str_contains($input, 'lama') || str_contains($input, 'setiap hari') || str_contains($input, 'iya') || str_contains($input, 'tidak semangat')) {
                    $response = "Apakah perasaan ini juga memengaruhi tidurmu (sulit tidur/terlalu banyak tidur) atau nafsu makanmu?";
                    $session->set('step', 11);
                } else {
                    $response = "Terima kasih sudah berbagi. Ingat, tidak apa-apa untuk tidak baik-baik saja. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step'); // Reset percakapan
                }
                break;

            case 11: // Setelah pertanyaan tentang tidur/nafsu makan
                // Pertanyaan: "Apakah perasaan ini juga memengaruhi tidurmu (sulit tidur/terlalu banyak tidur) atau nafsu makanmu?"
                if (str_contains($input, 'iya') || str_contains($input, 'sulit') || str_contains($input, 'tidak nafsu') || str_contains($input, 'berlebihan')) {
                    $response = "Perasaan tidak berharga atau putus asa juga sering muncul?";
                    $session->set('step', 12);
                } else {
                    $response = "Baik, tetap jaga kesehatan mental ya. Aku di sini kalau kamu butuh teman bicara.";
                    $session->remove('step');
                }
                break;

            case 12: // Setelah pertanyaan tentang perasaan tidak berharga
                // Pertanyaan: "Perasaan tidak berharga atau putus asa juga sering muncul?"
                if (str_contains($input, 'iya') || str_contains($input, 'sering')) {
                    $response = "Gejala yang kamu alami sangat mirip dengan depresi. Penting untuk mencari dukungan. Kamu bisa mencoba berbicara dengan teman dekat, keluarga, atau mencari konselor kampus. Aku bisa bantu carikan info kontak konseling jika kamu mau.";
                } else {
                    $response = "Terima kasih sudah berbagi. Ingat, tidak apa-apa untuk tidak baik-baik saja. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step'); // Reset setelah alur selesai
                break;

            // --- Alur Kecemasan (Step 20-29) ---
            case 20: // Setelah deteksi awal cemas/gelisah
                // Pertanyaan: "Kecemasan ini sering muncul kapan? Apakah ada pemicu tertentu?"
                if (str_contains($input, 'kuliah') || str_contains($input, 'tugas') || str_contains($input, 'sosial') || str_contains($input, 'tiba-tiba') || str_contains($input, 'iya')) {
                    $response = "Apakah kamu juga merasakan gejala fisik seperti detak jantung cepat, sesak napas, atau gemetar saat cemas?";
                    $session->set('step', 21);
                } else {
                    $response = "Baik, coba perhatikan pemicunya ya. Latihan pernapasan bisa sangat membantu. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step');
                }
                break;

            case 21: // Setelah pertanyaan tentang pemicu
                // Pertanyaan: "Apakah kamu juga merasakan gejala fisik seperti detak jantung cepat, sesak napas, atau gemetar saat cemas?"
                if (str_contains($input, 'iya') || str_contains($input, 'sering')) {
                    $response = "Gejala fisik itu umum saat cemas. Coba latihan pernapasan 4-7-8 untuk menenangkan diri. Jika terus berlanjut dan mengganggu, sangat disarankan untuk berkonsultasi dengan profesional kesehatan mental.";
                } else {
                    $response = "Baik, coba perhatikan pemicunya ya. Latihan pernapasan bisa sangat membantu. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step');
                break;

            // --- Alur Burnout/Stres Akademik (Step 30-39) ---
            case 30: // Setelah deteksi awal stress/lelah
                // Pertanyaan: "Seberapa sering kamu merasakan ini? Apakah ini terkait dengan tekanan kuliah atau tugas?"
                if (str_contains($input, 'iya') || str_contains($input, 'kuliah') || str_contains($input, 'tugas') || str_contains($input, 'sering')) {
                    $response = "Apakah kamu merasa motivasimu menurun drastis dan sulit menyelesaikan tugas yang biasanya bisa kamu kerjakan?";
                    $session->set('step', 31);
                } else {
                    $response = "Tetap jaga keseimbangan ya antara belajar dan istirahat. Jangan sampai terlalu lelah. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step');
                }
                break;

            case 31: // Setelah pertanyaan tentang frekuensi/kaitan akademik
                // Pertanyaan: "Apakah kamu merasa motivasimu menurun drastis dan sulit menyelesaikan tugas yang biasanya bisa kamu kerjakan?"
                if (str_contains($input, 'iya') || str_contains($input, 'menurun') || str_contains($input, 'sulit')) {
                    $response = "Kamu mungkin mengalami burnout akademik. Penting untuk mengatur ulang prioritas, mengambil istirahat, dan mencari dukungan. Jangan ragu bicara dengan dosen pembimbing atau konselor kampus. Istirahat itu penting!";
                } else {
                    $response = "Bagus kalau tidak terlalu parah. Tapi tetap penting untuk istirahat ya. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step');
                break;

            // --- Alur Masalah Tidur (Step 40-49) ---
            case 40: // Setelah deteksi awal masalah tidur
                // Pertanyaan: "Sudah berapa lama kamu mengalami masalah tidur ini? Apakah kamu kesulitan memulai tidur atau sering terbangun di malam hari?"
                if (str_contains($input, 'lama') || str_contains($input, 'sulit memulai') || str_contains($input, 'sering terbangun') || str_contains($input, 'iya')) {
                    $response = "Masalah tidur bisa sangat memengaruhi mood dan fokus. Coba buat rutinitas tidur yang konsisten, hindari kafein sebelum tidur, dan pastikan kamar tidurmu nyaman. Jika tidak membaik, konsultasi dengan dokter bisa membantu.";
                } else {
                    $response = "Semoga masalah tidurmu segera membaik ya. Coba perhatikan kebiasaan tidurmu. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step');
                break;

            default: // Jika step tidak dikenali atau rule-based tidak cocok, fallback ke Gemini API
                $response = $this->getGeminiResponse($input);
                $session->remove('step'); // Reset step setelah menggunakan Gemini
                break;
        }

        return $this->response->setJSON(['response' => $response]);
    }

    private function getGeminiResponse($userMessage)
    {
        // Ganti dengan API Key Gemini Anda
        // Disarankan untuk menyimpan API Key di file .env dan mengaksesnya menggunakan getenv()
        $apiKey = getenv('GEMINI_API_KEY');
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        // Prompt Engineering untuk mengunci domain
        $systemInstruction = "Anda adalah chatbot dukungan mental health yang ramah, empati, dan informatif.
            Fokuslah pada memberikan dukungan emosional umum, strategi coping dasar, dan informasi umum tentang konsep mental health.
            Prioritas utama Anda adalah keamanan dan akurasi.
            Jangan pernah memberikan diagnosis medis, saran medis, resep, atau nomor darurat krisis.
            Jika pertanyaan di luar cakupan kesehatan mental atau tidak pantas, tolak dengan sopan dan sarankan untuk mencari bantuan profesional atau sumber daya yang relevan.
            Jawab pertanyaan pengguna dengan singkat dan jelas.";

        $chatHistory = [
            [
                "role" => "user",
                "parts" => [
                    ["text" => $systemInstruction . "\n\nPertanyaan: " . $userMessage]
                ]
            ]
        ];

        $payload = [
            "contents" => $chatHistory,
            // Anda bisa menambahkan generationConfig jika ingin respons terstruktur (misal JSON)
            // "generationConfig" => [
            //     "responseMimeType" => "application/json",
            //     "responseSchema" => [
            //         "type" => "OBJECT",
            //         "properties" => [
            //             "reply" => ["type" => "STRING"],
            //             "suggestion" => ["type" => "STRING"]
            //         ]
            //     ]
            // ]
        ];

        $client = new Client(); // Inisialisasi Guzzle Client

        try {
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $payload
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Pastikan struktur respons sesuai dengan yang diharapkan dari Gemini API
            if (isset($body['candidates'][0]['content']['parts'][0]['text'])) {
                $geminiResponse = $body['candidates'][0]['content']['parts'][0]['text'];

                // Tambahkan validasi atau filter tambahan pada respons Gemini di sini
                // Misalnya, cek kata kunci yang tidak diinginkan atau format respons.
                // Jika respons tidak sesuai, berikan pesan fallback.

                return $geminiResponse;
            } else {
                log_message('error', 'Gemini API response structure unexpected: ' . json_encode($body));
                return "Maaf, ada masalah saat memproses permintaan Anda. Silakan coba lagi.";
            }
        } catch (RequestException $e) {
            // Tangani error dari API request (misal: koneksi, API key salah, dll.)
            log_message('error', 'Gemini API Request Error: ' . $e->getMessage());
            return "Maaf, saya tidak bisa terhubung dengan layanan AI saat ini. Silakan coba lagi nanti.";
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            log_message('error', 'General Error in Gemini API call: ' . $e->getMessage());
            return "Terjadi kesalahan. Mohon coba lagi.";
        }
    }

    private function getBotResponse($input)
    {
        // Metode ini sekarang hanya sebagai placeholder atau untuk respons sangat dasar
        // yang tidak memerlukan alur step-by-step atau Gemini.
        if (str_contains($input, 'stress') !== false) {
            return 'Coba tarik napas dalam-dalam dan beri dirimu waktu untuk istirahat.';
        } elseif (str_contains($input, 'cemas') !== false || str_contains($input, 'anxiety') !== false) {
            return 'Kamu bisa coba teknik pernapasan 4-7-8. Mau aku bantu pandu?';
        } elseif (str_contains($input, 'halo') !== false) {
            return 'Halo juga! 😊 Apa yang sedang kamu rasakan hari ini?';
        } else {
            return 'Maaf, aku belum memahami itu. Bisa dijelaskan lebih lanjut?';
        }
    }
}
