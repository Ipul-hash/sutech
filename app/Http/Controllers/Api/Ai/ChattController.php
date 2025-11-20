<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ticket;
use Illuminate\Support\Str;

class ChattController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $apiKey = config('gemini.api_key');
            if (!$apiKey) {
                return response()->json([
                    "success" => false,
                    "error" => "API Key tidak ditemukan."
                ], 500);
            }

            $message = strtolower($request->message);

            // =========================================================
            // 0. CEK JIKA PERTANYAAN TUTORIAL (OVERRIDE MODE TIKET)
            // =========================================================
            $tutorialKeywordsStart = ['cara', 'bagaimana', 'gimana', 'tutorial', 'buat', 'membuat'];
            $isTutorial = false;

            foreach ($tutorialKeywordsStart as $tks) {
                if (Str::startsWith($message, $tks)) {
                    $isTutorial = true;
                    break;
                }
            }

            // =========================================================
            // 1. CEK APAKAH PERLU MASUK TIKET MODE
            // =========================================================
            $ticketKeywords = [
                'tiket','ticket','status','kategori','prioritas','priority',
                'jumlah','report','laporan','open','pending','progress','resolved'
            ];

            $isTicketQuestion = false;

            foreach ($ticketKeywords as $k) {
                if (Str::contains($message, $k)) {
                    $isTicketQuestion = true;
                    break;
                }
            }

            // Override: Jika tutorial → TIDAK masuk ticket mode
            if ($isTutorial) {
                $isTicketQuestion = false;
            }

            // =========================================================
            // 2. AMBIL DATA JIKA BUTUH MODE TIKET
            // =========================================================
            $tickets = [];

            if ($isTicketQuestion) {
                $tickets = Ticket::select(
                    'id','user_id','assigned_to','category','priority',
                    'room_id','title','description','root_cause',
                    'status','sla_deadline','escalated_to','closed_at','created_at'
                )
                ->orderBy('created_at','desc')
                ->limit(50)
                ->get()
                ->toArray();
            }

            // =========================================================
            // 3. PROMPT MODE TIKET
            // =========================================================
            if ($isTicketQuestion) {
                $prompt = "
Kamu adalah AI Helpdesk profesional.

Gunakan data tiket berikut untuk menjawab:
------------------------------------------------
".json_encode($tickets, JSON_PRETTY_PRINT)."
------------------------------------------------

ATURAN:
- Jawab **hanya** berdasarkan data JSON.
- Jika user tanya jumlah → hitung dari JSON.
- Jika tanya filter (status/kategori/lokasi/prioritas) → filter JSON.
- Jika data tidak ditemukan → jawab: \"Tidak ditemukan data yang sesuai.\".
- Format jawaban WAJIB rapi:
    - Gunakan bullet atau numbering
    - Pisahkan poin dengan 1 baris kosong
    - Jangan jawab bertele-tele

User: {$request->message}
";
            } 
            // =========================================================
            // 4. PROMPT MODE GENERAL / TUTOR
            // =========================================================
            else {

                $prompt = "
Kamu adalah AI Assistant untuk Sistem Helpdesk internal.

Jawablah pertanyaan user dengan gaya santai, jelas, dan rapi.
Gunakan struktur berikut:

- Gunakan poin 1, 2, 3 jika langkah-langkah
- Jika poin 1 selesai, maka beri 1 space baris kosong agar rapih
- Jangan terlalu panjang

=== KNOWLEDGE BASE HELP DESK ===

1. **Cara Membuat Tiket**
   - Klik menu \"Buat Tiket\"
   - Isi form: judul, deskripsi, kategori, prioritas, lokasi/ruangan
   - Klik Submit → tiket masuk status open
   - Dan tunggu tim untuk memproses

2. **Detail Tiket**
   - Menampilkan semua data tiket
   - Agent bisa update: in progress, pending, resolved
   - Wajib isi alasan pending / solusi saat resolved

3. **Claim Ticket**
   - Agent klik tombol \"Ambil Tiket\"
   - Sistem assign ke agent tersebut

4. **Update Progress**
   - Agent bisa update progres, status, dan upload file

5. **Alur Ticket**
   - open → in_progress → pending/resolved

=== ATURAN KHUSUS ===
- Jika user bertanya \"cara\" → berikan langkah-langkah
- Format harus rapi dan mudah dibaca
- Jangan jawab \"tidak ada data\" di mode ini

User: {$request->message}
";
            }

            // =========================================================
            // 5. KIRIM KE GEMINI
            // =========================================================
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "x-goog-api-key" => $apiKey,
            ])->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent",
                [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $prompt]
                            ]
                        ]
                    ]
                ]
            );

            $json = $response->json();

            $reply = $json['candidates'][0]['content']['parts'][0]['text']
                ?? "AI tidak merespons.";

            // =========================================================
            // 6. INTENT EXPORT LAPORAN
            // =========================================================
            if (Str::contains($message, ['export','excel','laporan'])) {
                return response()->json([
                    'success' => true,
                    'intent' => 'export_excel'
                ]);
            }

            // =========================================================
            // 7. KIRIM RESPON
            // =========================================================
            return response()->json([
                "success" => true,
                "reply" => $reply,
                "mode"  => $isTicketQuestion ? "ticket_mode" : "general_mode"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
