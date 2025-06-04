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

        // Simpan riwayat percakapan untuk potensi penggunaan Gemini (opsional, jika ingin AI mengingat konteks)
        // $chatHistory = $session->get('chat_history') ?? [];

        // Logika percakapan berdasarkan 'step'
        switch ($step) {
            case 0: // Tahap 0: Deteksi keluhan utama atau sapaan awal
                if (str_contains($input, 'halo') || str_contains($input, 'hai') || str_contains($input, 'apa kabar')) {
                    $response = "Halo juga! 😊 Apa yang sedang kamu rasakan hari ini? Ceritakan saja, aku di sini untuk mendengarkan.";
                    // Tetap di step 0 agar bisa mendeteksi keluhan utama di balasan selanjutnya
                } elseif (str_contains($input, 'sedih') || str_contains($input, 'depresi') || str_contains($input, 'putus asa') || str_contains($input, 'tidak semangat') || str_contains($input, 'menangis')) {
                    $response = "Aku turut prihatin kamu merasakan itu. Sudah berapa lama perasaan sedih ini muncul? Apakah ada hal lain yang membuatmu kehilangan minat?";
                    $session->set('step', 10); // Lanjut ke alur Depresi
                } elseif (str_contains($input, 'cemas') || str_contains($input, 'gelisah') || str_contains($input, 'panik') || str_contains($input, 'khawatir') || str_contains($input, 'takut')) {
                    $response = "Aku mengerti perasaan cemas itu tidak nyaman. Kecemasan ini sering muncul kapan? Apakah ada pemicu tertentu?";
                    $session->set('step', 20); // Lanjut ke alur Kecemasan
                } elseif (str_contains($input, 'stress') || str_contains($input, 'lelah') || str_contains($input, 'burnout') || str_contains($input, 'sulit fokus') || str_contains($input, 'tugas menumpuk') || str_contains($input, 'tertekan')) {
                    $response = "Aku paham rasanya lelah dan sulit fokus. Seberapa sering kamu merasakan ini? Apakah ini terkait dengan tekanan kuliah atau tugas?";
                    $session->set('step', 30); // Lanjut ke alur Burnout/Stres Akademik
                } elseif (str_contains($input, 'tidur') || str_contains($input, 'insomnia') || str_contains($input, 'begadang') || str_contains($input, 'sulit tidur')) {
                    $response = "Sulit tidur memang tidak nyaman. Sudah berapa lama kamu mengalami masalah tidur ini? Apakah kamu kesulitan memulai tidur atau atau sering terbangun di malam hari?";
                    $session->set('step', 40); // Lanjut ke alur Masalah Tidur
                } elseif (str_contains($input, 'palsu') || str_contains($input, 'pura-pura') || str_contains($input, 'baik-baik saja') || str_contains($input, 'sembunyi')) {
                    $response = "Aku mengerti. Terkadang kita merasa harus terlihat baik-baik saja di luar, padahal di dalam ada banyak tekanan. Apakah kamu sering merasa seperti ini?";
                    $session->set('step', 50); // Lanjut ke alur Duck Syndrome
                } elseif (str_contains($input, 'makan') || str_contains($input, 'berat badan') || str_contains($input, 'diet') || str_contains($input, 'kontrol makanan') || str_contains($input, 'cemas makanan')) {
                    $response = "Aku perhatikan kamu menyebut tentang makan atau berat badan. Bisakah kamu ceritakan lebih lanjut tentang kekhawatiranmu terkait hal itu?";
                    $session->set('step', 60); // Lanjut ke alur Eating Disorder
                } else {
                    // Jika tidak ada kata kunci yang cocok di rule-based, fallback ke Gemini API
                    $response = $this->getGeminiResponse($input);
                    $session->remove('step'); // Reset step setelah menggunakan Gemini
                }
                break;

            // --- Alur Depresi (Step 10-19) ---
            case 10: // Setelah deteksi awal sedih/depresi
                // Pertanyaan: "Sudah berapa lama perasaan sedih ini muncul? Apakah ada hal lain yang membuatmu kehilangan minat?"
                if (str_contains($input, 'lama') || str_contains($input, 'setiap hari') || str_contains($input, 'iya') || str_contains($input, 'tidak semangat') || str_contains($input, 'bulan') || str_contains($input, 'minggu')) {
                    $response = "Apakah perasaan ini juga memengaruhi tidurmu (sulit tidur/terlalu banyak tidur) atau nafsu makanmu (berlebihan/tidak nafsu makan)?";
                    $session->set('step', 11);
                } else {
                    $response = "Terima kasih sudah berbagi. Ingat, tidak apa-apa untuk tidak baik-baik saja. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step'); // Reset percakapan
                }
                break;

            case 11: // Setelah pertanyaan tentang tidur/nafsu makan
                // Pertanyaan: "Apakah perasaan ini juga memengaruhi tidurmu (sulit tidur/terlalu banyak tidur) atau nafsu makanmu (berlebihan/tidak nafsu makan)?"
                if (str_contains($input, 'iya') || str_contains($input, 'sulit') || str_contains($input, 'tidak nafsu') || str_contains($input, 'berlebihan') || str_contains($input, 'kurang') || str_contains($input, 'banyak')) {
                    $response = "Perasaan tidak berharga, putus asa, atau bahkan pikiran tentang menyakiti diri sendiri juga sering muncul?";
                    $session->set('step', 12);
                } else {
                    $response = "Baik, tetap jaga kesehatan mental ya. Aku di sini kalau kamu butuh teman bicara.";
                    $session->remove('step');
                }
                break;

            case 12: // Setelah pertanyaan tentang perasaan tidak berharga
                // Pertanyaan: "Perasaan tidak berharga, putus asa, atau bahkan pikiran tentang menyakiti diri sendiri juga sering muncul?"
                if (str_contains($input, 'iya') || str_contains($input, 'sering') || str_contains($input, 'putus asa') || str_contains($input, 'tidak berharga') || str_contains($input, 'bunuh diri') || str_contains($input, 'menyakiti diri')) {
                    $response = "Gejala yang kamu alami sangat mirip dengan depresi. Penting untuk mencari dukungan profesional. Kamu bisa mencoba berbicara dengan konselor kampus atau psikolog. Jika ada pikiran menyakiti diri, segera hubungi nomor darurat 112 atau hotline bunuh diri di [masukkan nomor hotline yang relevan di Indonesia jika ada, contoh: 119 ext. 8]."; // Ganti dengan nomor hotline yang valid
                } else {
                    $response = "Terima kasih sudah berbagi. Ingat, tidak apa-apa untuk tidak baik-baik saja. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step'); // Reset setelah alur selesai
                break;

            // --- Alur Kecemasan (Step 20-29) ---
            case 20: // Setelah deteksi awal cemas/gelisah
                // Pertanyaan: "Kecemasan ini sering muncul kapan? Apakah ada pemicu tertentu?"
                if (str_contains($input, 'kuliah') || str_contains($input, 'tugas') || str_contains($input, 'sosial') || str_contains($input, 'tiba-tiba') || str_contains($input, 'iya') || str_contains($input, 'ujian') || str_contains($input, 'presentasi')) {
                    $response = "Apakah kamu juga merasakan gejala fisik seperti detak jantung cepat, sesak napas, berkeringat dingin, atau gemetar saat cemas?";
                    $session->set('step', 21);
                } else {
                    $response = "Baik, coba perhatikan pemicunya ya. Latihan pernapasan bisa sangat membantu. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step');
                }
                break;

            case 21: // Setelah pertanyaan tentang pemicu
                // Pertanyaan: "Apakah kamu juga merasakan gejala fisik seperti detak jantung cepat, sesak napas, berkeringat dingin, atau gemetar saat cemas?"
                if (str_contains($input, 'iya') || str_contains($input, 'sering') || str_contains($input, 'jantung') || str_contains($input, 'sesak') || str_contains($input, 'gemetar') || str_contains($input, 'panik')) {
                    $response = "Gejala fisik itu umum saat cemas. Coba latihan pernapasan 4-7-8 untuk menenangkan diri. Jika terus berlanjut dan mengganggu aktivitasmu, sangat disarankan untuk berkonsultasi dengan profesional kesehatan mental.";
                } else {
                    $response = "Baik, coba perhatikan pemicunya ya. Latihan pernapasan bisa sangat membantu. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step');
                break;

            // --- Alur Burnout/Stres Akademik (Step 30-39) ---
            case 30: // Setelah deteksi awal stress/lelah
                // Pertanyaan: "Seberapa sering kamu merasakan ini? Apakah ini terkait dengan tekanan kuliah atau tugas?"
                if (str_contains($input, 'iya') || str_contains($input, 'kuliah') || str_contains($input, 'tugas') || str_contains($input, 'sering') || str_contains($input, 'menumpuk') || str_contains($input, 'tekanan')) {
                    $response = "Apakah kamu merasa motivasimu menurun drastis, merasa sinis dengan kuliah, dan sulit menyelesaikan tugas yang biasanya bisa kamu kerjakan?";
                    $session->set('step', 31);
                } else {
                    $response = "Tetap jaga keseimbangan ya antara belajar dan istirahat. Jangan sampai terlalu lelah. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step');
                }
                break;

            case 31: // Setelah pertanyaan tentang frekuensi/kaitan akademik
                // Pertanyaan: "Apakah kamu merasa motivasimu menurun drastis, merasa sinis dengan kuliah, dan sulit menyelesaikan tugas yang biasanya bisa kamu kerjakan?"
                if (str_contains($input, 'iya') || str_contains($input, 'menurun') || str_contains($input, 'sulit') || str_contains($input, 'sinis') || str_contains($input, 'frustasi')) {
                    $response = "Kamu mungkin mengalami burnout akademik. Penting untuk mengatur ulang prioritas, mengambil istirahat, dan mencari dukungan. Jangan ragu bicara dengan dosen pembimbing atau konselor kampus. Istirahat itu penting!";
                } else {
                    $response = "Bagus kalau tidak terlalu parah. Tapi tetap penting untuk istirahat ya. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step');
                break;

            // --- Alur Masalah Tidur (Step 40-49) ---
            case 40: // Setelah deteksi awal masalah tidur
                // Pertanyaan: "Sudah berapa lama kamu mengalami masalah tidur ini? Apakah kamu kesulitan memulai tidur atau atau sering terbangun di malam hari?"
                if (str_contains($input, 'lama') || str_contains($input, 'sulit memulai') || str_contains($input, 'sering terbangun') || str_contains($input, 'iya') || str_contains($input, 'kurang')) {
                    $response = "Apakah masalah tidur ini memengaruhi konsentrasimu di kampus atau moodmu di siang hari?";
                    $session->set('step', 41);
                } else {
                    $response = "Semoga masalah tidurmu segera membaik ya. Coba perhatikan kebiasaan tidurmu. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step');
                }
                break;

            case 41: // Setelah pertanyaan tentang durasi/jenis masalah tidur
                // Pertanyaan: "Apakah masalah tidur ini memengaruhi konsentrasimu di kampus atau moodmu di siang hari?"
                if (str_contains($input, 'iya') || str_contains($input, 'konsentrasi') || str_contains($input, 'mood')) {
                    $response = "Masalah tidur bisa sangat memengaruhi mood dan fokus. Coba buat rutinitas tidur yang konsisten, hindari kafein sebelum tidur, dan pastikan kamar tidurmu nyaman. Jika tidak membaik setelah mencoba hal-hal ini, konsultasi dengan dokter bisa membantu.";
                } else {
                    $response = "Baik, semoga masalah tidurmu segera membaik ya. Tetap perhatikan pola tidurmu. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step');
                break;

            // --- Alur Duck Syndrome (Step 50-59) ---
            case 50: // Setelah deteksi awal "baik-baik saja" / "pura-pura"
                // Pertanyaan: "Apakah kamu sering merasa seperti ini?"
                if (str_contains($input, 'iya') || str_contains($input, 'sering') || str_contains($input, 'setiap hari')) {
                    $response = "Perasaan ini sering muncul karena tekanan untuk selalu sempurna atau takut mengecewakan. Apakah kamu merasa harus selalu menunjukkan sisi terbaikmu, meskipun sedang tidak baik-baik saja?";
                    $session->set('step', 51);
                } else {
                    $response = "Aku senang kamu tidak selalu merasa seperti itu. Ingat, tidak apa-apa untuk tidak sempurna. Aku di sini kalau kamu butuh teman bicara lagi.";
                    $session->remove('step');
                }
                break;

            case 51: // Setelah pertanyaan tentang tekanan/perfeksionisme
                // Pertanyaan: "Apakah kamu merasa harus selalu menunjukkan sisi terbaikmu, meskipun sedang tidak baik-baik saja?"
                if (str_contains($input, 'iya') || str_contains($input, 'harus') || str_contains($input, 'perfeksionis')) {
                    $response = "Sangat penting untuk jujur pada diri sendiri dan orang terdekat. Tidak ada yang sempurna, dan mencari bantuan atau sekadar berbagi perasaan tidak akan membuatmu terlihat lemah. Cobalah untuk lebih terbuka dan bersikap baik pada dirimu sendiri.";
                } else {
                    $response = "Itu bagus! Ingat, validasi diri datang dari dalam, bukan dari pandangan orang lain. Aku di sini kalau kamu butuh teman bicara lagi.";
                }
                $session->remove('step');
                break;

            // --- Alur Eating Disorder (Step 60-69) ---
            // Catatan: Alur ini akan sangat singkat dan langsung merujuk ke profesional karena sensitif dan kompleksitas ED.
            case 60: // Setelah deteksi awal makan/berat badan
                // Pertanyaan: "Bisakah kamu ceritakan lebih lanjut tentang kekhawatiranmu terkait hal itu?"
                if (str_contains($input, 'kontrol') || str_contains($input, 'berat badan') || str_contains($input, 'muntah') || str_contains($input, 'diet') || str_contains($input, 'makan berlebihan') || str_contains($input, 'bentuk tubuh') || str_contains($input, 'gendut') || str_contains($input, 'kurus')) {
                    $response = "Aku memahami kekhawatiranmu ini. Isu terkait makan dan berat badan seringkali sangat kompleks dan memerlukan penanganan khusus. Sangat penting bagi kamu untuk segera mencari bantuan dari profesional kesehatan yang terlatih dalam gangguan makan, seperti psikolog, psikiater, atau ahli gizi klinis. Mereka bisa memberikan dukungan dan penanganan yang tepat.";
                } else {
                    $response = "Aku mengerti. Penting untuk selalu memiliki hubungan yang sehat dengan makanan dan tubuh. Jika ada kekhawatiran serius, jangan ragu untuk mencari bantuan profesional.";
                }
                $session->remove('step'); // Selalu reset setelah alur ini
                break;

            default: // Jika step tidak dikenali atau rule-based tidak cocok, fallback ke Gemini API
                $response = $this->getGeminiResponse($input);
                $session->remove('step'); // Reset step setelah menggunakan Gemini
                break;
        }

        // Tambahkan userMessage ke chat history sebelum respon bot
        // if ($step == 0) { // Hanya jika tidak dalam alur step-by-step
        //     $chatHistory[] = ["role" => "user", "parts" => [["text" => $input]]];
        //     $chatHistory[] = ["role" => "model", "parts" => [["text" => $response]]];
        //     $session->set('chat_history', $chatHistory);
        // }


        return $this->response->setJSON(['response' => $response]);
    }

    /**
     * Metode untuk memanggil Gemini API dan mendapatkan respons.
     * Menggunakan Prompt Engineering untuk mengunci domain pertanyaan.
     * @param string $userMessage Pesan dari pengguna.
     * @return string Respons dari Gemini atau pesan fallback.
     */
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
            Jawab pertanyaan pengguna dengan singkat dan jelas.
            jika terdapat kata yang di bold jangan menggunakan simbol **, gunakan huruf kapital.";

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
                $forbiddenKeywords = ['diagnosis', 'obat', 'resep', 'bunuh diri', 'self-harm', 'mati saja'];
                foreach ($forbiddenKeywords as $keyword) {
                    if (str_contains(strtolower($geminiResponse), $keyword)) {
                        log_message('warning', 'Gemini response contained forbidden keyword: ' . $keyword . ' for user: ' . $userMessage);
                        return "Maaf, saya tidak bisa membahas topik itu secara detail. Jika Anda membutuhkan bantuan medis atau berada dalam krisis, silakan hubungi profesional kesehatan atau nomor darurat.";
                    }
                }

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

    /**
     * Metode helper untuk mendapatkan respons bot (tidak digunakan dalam alur step-by-step ini,
     * tapi bisa dipertahankan untuk respons umum jika tidak ada step yang cocok).
     * @param string $input Pesan dari pengguna.
     * @return string Respons dari bot.
     */
    private function getBotResponse($input)
    {
        // Metode ini sekarang hanya sebagai placeholder atau untuk respons sangat dasar
        // yang tidak memerlukan alur step-by-step atau Gemini.
        // Sebagian besar logika kini ada di switch($step) dan getGeminiResponse.
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
