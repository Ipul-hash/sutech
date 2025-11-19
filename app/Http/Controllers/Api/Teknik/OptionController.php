<?php

namespace App\Http\Controllers\Api\Teknik;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use App\Models\Team;
use App\Models\Position;
use Spatie\Permission\Models\Role;

class OptionController extends Controller
{
    public function getOptions()
    {
        try {
            // Ambil role dari Spatie (admin, agent, user, dst)
            $roles = Role::select('id', 'name')->orderBy('name')->get();

            // Ambil kategori tiket
            $categories = TicketCategory::select('id', 'name', 'slug')
                ->orderBy('name')
                ->get();

            // Ambil team (infra, simrs, jaringan, dll)
            $team = Team::select('id', 'name')
                ->orderBy('name')
                ->get();

            // Ambil posisi (perawat, dokter, staff, dll)
            $position = Position::select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Opsi berhasil dimuat',
                'data' => [
                    'roles'      => $roles,
                    'categories' => $categories,
                    'team'       => $team,
                    'position'   => $position,
                ]
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat opsi',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
