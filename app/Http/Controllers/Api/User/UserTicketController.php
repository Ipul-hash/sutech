<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Posittion;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserTicketController extends Controller
{
    /**
     * GET /api/user/my-tickets
     * Ambil semua tiket milik user login
     */
    public function index()
    {
        $user = Auth::user();
        $tickets = Ticket::with([
            'agent:id,name,email', // agent yang menangani
            'room:id,name',
            'position:id,name',
        ])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

        // Hitung statistik
        $stats = [
            'total' => $tickets->count(),
            'open' => $tickets->where('status', 'open')->count(),
            'in_progress' => $tickets->where('status', 'in_progress')->count(),
            'resolved' => $tickets->where('status', 'resolved')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'user_name' => $user->name,
                'stats' => $stats,
                'tickets' => $tickets
            ]
        ]);
    }

    /**
     * POST /api/user/tickets
     * User membuat tiket baru
     */
    public function store(Request $request)
{
    $user = Auth::user();
    
    // Validasi
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255|min:5',
        'description' => 'required|string|min:20',
        'room_id' => 'required|exists:rooms,id', // user pilih ruangan
        'priority' => 'required|in:low,medium,high,critical',
        'attachments' => 'nullable|array|max:5',
        'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
    }

    // Buat tiket — status "open", assigned_team_id = null (nanti diisi admin)
    $ticket = Ticket::create([
        'user_id' => $user->id,
        'position_id' => $user->position_id, // ✅ ambil dari user
        'room_id' => $request->room_id,      // ✅ user pilih ruangan
        'title' => $request->title,
        'description' => $request->description,
        'category' => $request->category, // ⚠️ admin yang isi nanti
        'assigned_team_id' => null, // ⚠️ admin yang isi nanti
        'priority' => $request->priority,
        'status' => 'open',
    ]);

    // Simpan lampiran
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('ticket_attachments', 'public');
            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Tiket berhasil dibuat! Menunggu penugasan oleh admin.',
        'data' => $ticket->load(['user', 'position', 'room', 'attachments'])
    ], 201);
}

    /**
     * GET /api/user/tickets/{id}
     * Detail tiket (hanya milik user)
     */
    public function show($id)
{
    // Validasi: pastikan tiket milik user login
    $ticket = Ticket::with([
        'agent:id,name,email',
        'room:id,name',
        'position:id,name', // ✅ Tambahkan ini
        'attachments:id,ticket_id,file_path,file_name,mime_type,file_size'
    ])->find($id);

    if (!$ticket || $ticket->user_id !== Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'Tiket tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $ticket
    ]);
}

    /**
     * Helper: Tentukan tim tujuan berdasarkan kategori & lokasi
     */
    private function determineTeam($category, $location)
    {
        // Sesuaikan dengan struktur tim di database Anda
        $teamMap = [
            'it' => 1,      // Tim IT
            'farmasi' => 2, // Tim Farmasi
            'igd' => 3,     // Tim IGD
            'umum' => 4,    // Tim Umum
        ];

        // Default ke Tim Umum jika tidak ditemukan
        return $teamMap[$location] ?? 4;
    }
}