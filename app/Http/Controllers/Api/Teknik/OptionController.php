<?php

namespace App\Http\Controllers\Api\Teknik;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use App\Models\Team;
use App\Models\Position;
use App\Models\Room;  // ⬅️ tambahkan ini
use Spatie\Permission\Models\Role;

class OptionController extends Controller
{
    public function getOptions()
    {
        try {
            // Roles
            $roles = Role::select('id', 'name')
                ->orderBy('name')
                ->get();

            // Kategori tiket
            $categories = TicketCategory::select('id', 'name', 'slug')
                ->orderBy('name')
                ->get();

            // Team (Infra, SIMRS, Jaringan, dll)
            $team = Team::select('id', 'name')
                ->orderBy('name')
                ->get();

            // Jabatan / posisi
            $position = Position::select('id', 'name')
                ->orderBy('name')
                ->get();

            // Ruangan Rumah Sakit
            $rooms = Room::select('id', 'name')
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
                    'rooms'      => $rooms,   // ⬅️ baru ditambahkan
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
