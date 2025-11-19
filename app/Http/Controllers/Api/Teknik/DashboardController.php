<?php

namespace App\Http\Controllers\Api\Teknik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function stats()
    {
        try {
            // Statistik utama
            $totalTickets = Ticket::count();
            $openTickets = Ticket::where('status', 'open')->count();
            $inProgressTickets = Ticket::where('status', 'in_progress')->count();
            $resolvedTickets = Ticket::where('status', 'resolved')->count();
            $closedTickets = Ticket::where('status', 'closed')->count();

            // Persentase perubahan (contoh dari bulan lalu)
            $lastMonthTotal = Ticket::whereMonth('created_at', now()->subMonth()->month)->count();
            $totalChange = $lastMonthTotal > 0 ? round((($totalTickets - $lastMonthTotal) / $lastMonthTotal) * 100) : 0;

            // Trend 6 bulan terakhir
            $trendData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $month = $date->format('M');
                
                $trendData[] = [
                    'month' => $month,
                    'open' => Ticket::where('status', 'open')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                    'progress' => Ticket::where('status', 'in_progress')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                    'resolved' => Ticket::where('status', 'resolved')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                ];
            }

            // Top categories (pie chart data)
            $topCategories = Ticket::select('category', DB::raw('count(*) as total'))
                ->groupBy('category')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(function($item) use ($totalTickets) {
                    return [
                        'category' => $item->category,
                        'total' => $item->total,
                        'percentage' => $totalTickets > 0 ? round(($item->total / $totalTickets) * 100) : 0
                    ];
                });

            // Tiket terbaru
            $recentTickets = Ticket::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Tiket butuh aksi (open + high/critical priority, atau unassigned)
            $urgentTickets = Ticket::with('user')
                ->where(function($query) {
                    $query->where('status', 'open')
                          ->whereIn('priority', ['high', 'critical']);
                })
                ->orWhereNull('assigned_to')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => [
                        'total' => $totalTickets,
                        'open' => $openTickets,
                        'in_progress' => $inProgressTickets,
                        'resolved' => $resolvedTickets,
                        'closed' => $closedTickets,
                        'total_change' => $totalChange,
                    ],
                    'trend' => $trendData,
                    'top_categories' => $topCategories,
                    'recent_tickets' => $recentTickets,
                    'urgent_tickets' => $urgentTickets,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trend(Request $request)
{
    try {
        $period = $request->input('period', 'week'); // today, week, month
        $data = [];

        switch ($period) {
            case 'today':
                // Data per jam hari ini (24 jam)
                for ($hour = 0; $hour < 24; $hour++) {
                    $startHour = now()->startOfDay()->addHours($hour);
                    $endHour = $startHour->copy()->addHour();
                    
                    $data[] = [
                        'label' => $startHour->format('H:00'),
                        'open' => Ticket::where('status', 'open')
                            ->whereBetween('created_at', [$startHour, $endHour])
                            ->count(),
                        'progress' => Ticket::where('status', 'in_progress')
                            ->whereBetween('created_at', [$startHour, $endHour])
                            ->count(),
                        'resolved' => Ticket::where('status', 'resolved')
                            ->whereBetween('created_at', [$startHour, $endHour])
                            ->count(),
                    ];
                }
                break;

            case 'week':
                // Data 7 hari terakhir
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $startDate = $date->copy()->startOfDay();
                    $endDate = $date->copy()->endOfDay();
                    
                    $data[] = [
                        'label' => $date->format('D'),
                        'open' => Ticket::where('status', 'open')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->count(),
                        'progress' => Ticket::where('status', 'in_progress')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->count(),
                        'resolved' => Ticket::where('status', 'resolved')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->count(),
                    ];
                }
                break;

            case 'month':
                // Data 30 hari terakhir (ambil per 5 hari)
                for ($i = 30; $i >= 0; $i -= 5) {
                    $date = now()->subDays($i);
                    $startDate = $date->copy()->startOfDay();
                    $endDate = $date->copy()->addDays(4)->endOfDay();
                    
                    $data[] = [
                        'label' => $date->format('d M'),
                        'open' => Ticket::where('status', 'open')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->count(),
                        'progress' => Ticket::where('status', 'in_progress')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->count(),
                        'resolved' => Ticket::where('status', 'resolved')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->count(),
                    ];
                }
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat trend data: ' . $e->getMessage()
        ], 500);
    }
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
