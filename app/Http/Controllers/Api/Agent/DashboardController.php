<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::user();

        if (!$agent->team_id) {
            return response()->json([
                'success' => false,
                'message' => 'Agent tidak memiliki team.'
            ], 403);
        }

        // Nama tim
        $team = Team::find($agent->team_id);
        $teamName = $team ? $team->name : 'Tim Tidak Diketahui';

        // Ambil semua tiket untuk tim ini
        $tickets = Ticket::with(['user:id,name,email', 'agent:id,name,email'])
            ->where('assigned_team_id', $agent->team_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik
        $stats = [
            'total' => $tickets->count(),
            'open' => $tickets->where('status', 'open')->count(),
            'in_progress' => $tickets->where('status', 'in_progress')->count(),
            'resolved' => $tickets->where('status', 'resolved')->count(),
        ];

        // Tiket yang belum diambil (open + assigned_to = null)
        $openTickets = $tickets->filter(fn($t) => $t->status === 'open' && $t->assigned_to === null)->values();

        // Tiket terbaru (max 10)
        $recentTickets = $tickets->take(10)->values();

        return response()->json([
            'success' => true,
            'data' => [
                'team_name' => $teamName,
                'stats' => $stats,
                'open_tickets' => $openTickets,
                'recent_tickets' => $recentTickets
            ]
        ]);
    }
}