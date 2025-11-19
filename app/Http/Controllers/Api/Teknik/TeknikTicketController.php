<?php

namespace App\Http\Controllers\Api\Teknik;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeknikTicketController extends Controller
{
    // GET /api/teknik-get
    public function index()
    {
        $tickets = Ticket::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data tiket berhasil diambil',
            'data' => $tickets
        ]);
    }

    // POST /api/teknik-get
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id'           => 'required|exists:users,id',
        'title'             => 'required|string|max:255|min:5',
        'description'       => 'required|string|min:20',
        'category'          => 'required|string',
        'location'          => 'required|string|in:igd,farmasi,it,umum',
        'priority'          => 'required|in:low,medium,high,critical',
        'assigned_team_id'  => 'required|exists:teams,id', 
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors'  => $validator->errors()
        ], 422);
    }

    $ticket = Ticket::create([
        'user_id'           => $request->user_id,
        'title'             => $request->title,
        'description'       => $request->description,
        'category'          => $request->category,
        'location'          => $request->location,
        'priority'          => $request->priority,
        'assigned_team_id'  => $request->assigned_team_id,
        'status'            => 'open',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Tiket berhasil dibuat',
        'data'    => $ticket->load('user')
    ], 201);
}

    // GET /api/teknik-get/{id}
    public function show($id)
    {
        $ticket = Ticket::with('user:id,name,email')->find($id);
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

    // PUT /api/teknik-get/{id}
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|min:20',
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
            'priority' => 'sometimes|in:low,medium,high,critical'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket->update($request->only(['title', 'description', 'status', 'priority']));

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diperbarui',
            'data' => $ticket->load('user')
        ]);
    }

    // DELETE /api/teknik-get/{id}
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dihapus'
        ]);
    }
}