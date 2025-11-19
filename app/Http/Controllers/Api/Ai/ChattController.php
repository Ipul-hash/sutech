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
                    "error" => "API key tidak ditemukan di .env atau config/gemini.php",
                ], 500);
            }

            $tickets = Ticket::select(
                'id','user_id','assigned_to','category','priority',
                'location','title','description','root_cause',
                'status','sla_deadline','escalated_to','closed_at','created_at'
            )
            ->orderBy('created_at', 'desc')
            ->limit(40)
            ->get()
            ->toArray();

            $prompt = "
Kamu adalah AI Helpdesk.
Gunakan data tiket JSON berikut sebagai referensi menjawab:

" . json_encode($tickets, JSON_PRETTY_PRINT) . "

Aturan:
- Jawab hanya dari data tiket di atas.
- Jika user bertanya jumlah tiket, hitung dari data JSON.
- Jika bertanya berdasarkan status/kategori/lokasi, filter datanya.
- Jika tidak ada hasil, jawab: 'Tidak ditemukan data yang sesuai.'
- Jawab singkat, jelas, dan rapi.

User: {$request->message}
";

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

            $reply = null;

            if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                $reply = $json['candidates'][0]['content']['parts'][0]['text'];
            } elseif (isset($json['candidates'][0]['content'][0]['parts'][0]['text'])) {
                $reply = $json['candidates'][0]['content'][0]['parts'][0]['text'];
            } elseif (!empty($json['candidates'][0])) {
                $reply = json_encode($json['candidates'][0]);
            }

            if (!$reply) {
                $reply = "AI tidak memberikan respons. Coba pertanyaan lebih singkat.";
            }

            if (Str::contains(strtolower($request->message), ['excel', 'export', 'laporan'])) {
                    return response()->json([
                    'success' => true,
                    'intent' => 'export_excel'
                ]);
            }


            return response()->json([
                "success" => true,
                "reply" => $reply,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage(),
            ], 500);
        }
    }
}
