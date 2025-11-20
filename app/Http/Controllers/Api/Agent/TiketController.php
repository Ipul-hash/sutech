<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketNote;
use App\Models\Position;
use App\Models\User;
use App\Models\TicketProgress;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TiketController extends Controller
{
    /**
     * GET /api/agent/tickets
     * Hanya menampilkan tiket sesuai team agent
     */
    public function index()
{
    $agent = Auth::user();

    if (!$agent->team_id) {
        return response()->json([
            'success' => false,
            'message' => 'Agent tidak memiliki team. Hubungi admin.'
        ], 403);
    }

    $tickets = Ticket::with('user:id,name,email')
        ->where('assigned_team_id', $agent->team_id) 
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $tickets
    ]);
}

    /**
     * GET /api/agent/tickets/{id}
     */
    public function show($id)
    {
        $ticket = Ticket::with([
            'user:id,name,email',
        ])->find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail tiket',
            'data' => $ticket
        ]);
    }

    public function store(Request $request)
{
    $agent = Auth::user();
    // Validasi agent punya team & posisi
    if (!$agent->team_id || !$agent->position_id) {
        return response()->json([
            'success' => false,
            'message' => 'Profil agent belum lengkap. Hubungi admin.'
        ], 403);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255|min:5',
        'description' => 'required|string|min:20',
        'category' => 'required|string',
            'position_id' => 'required|exists:positions,id', // ✅ harus ada

        'room_id' => 'required|exists:rooms,id',
        'priority' => 'required|in:low,medium,high,critical',
        'attachments' => 'nullable|array|max:5',
        'attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    // Buat tiket
    $ticket = Ticket::create([
        'user_id' => $agent->id,
        'position_id' => $agent->position_id,
        'assigned_team_id' => $agent->team_id,
        'title' => $request->title,
        'description' => $request->description,
        'category' => $request->category,
        'room_id' => $request->room_id,
        'priority' => $request->priority,
        'status' => 'open',
    ]);

    // Simpan lampiran jika ada
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
        'message' => 'Tiket berhasil dibuat',
        'data' => $ticket->load(['user', 'position', 'room', 'attachments'])
    ], 201);
}

    /**
     * POST /api/agent/tickets/{id}/take
     * Agent ambil tiket
     */
    public function takeTicket($id)
    {
        $agent = Auth::user();
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        // Cek apakah agen sudah ambil
        if ($ticket->assigned_to) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah diambil oleh agent lain'
            ], 409);
        }

        $ticket->assigned_to = $agent->id;
        $ticket->status = 'in_progress';
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diambil',
            'data' => $ticket
        ]);
    }

    /**
     * PUT /api/agent/tickets/{id}
     * Update status, priority, root_cause, escalate
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'in:open,in_progress,resolved,closed',
            'priority' => 'in:low,medium,high,critical',
            'root_cause' => 'nullable|string',
            'escalated_to' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket->update($request->only([
            'status',
            'priority',
            'root_cause',
            'escalated_to',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diperbarui',
            'data' => $ticket
        ]);
    }

    /**
     * POST /api/agent/tickets/{id}/notes
     * Agent mengirim chat ke ticket
     */
    public function addNote(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'note' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $note = TicketNote::create([
            'ticket_id' => $id,
            'created_by' => Auth::id(),
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan',
            'data' => $note
        ], 201);
    }

    /**
     * POST /api/agent/tickets/{id}/progress
     * Agent update progress pekerjaan
     */
    public function addProgress(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'progress' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $progress = TicketProgress::create([
            'ticket_id' => $id,
            'updated_by' => Auth::id(),
            'progress' => $request->progress
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil ditambahkan',
            'data' => $progress
        ]);
    }
}
