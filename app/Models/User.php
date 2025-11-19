<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name', 
        'email', 
        'password',
        'position_id',
        'team_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* =====================================================
     *  RELASI TIKET
     * ===================================================== */

    // Ticket dibuat oleh user (pelapor)
    public function ticketsReported()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    // Ticket yang assign ke agent tertentu
    public function ticketsAssigned()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    // Catatan tiket
    public function notes()
    {
        return $this->hasMany(TicketNote::class, 'created_by');
    }

    // Progress tiket
    public function progress()
    {
        return $this->hasMany(TicketProgress::class, 'updated_by');
    }


    /* =====================================================
     *  RELASI POSITION (Perawat, Dokter, Staff)
     * ===================================================== */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /* =====================================================
     *  RELASI TEAM (Infra, SIMRS — khusus Agent)
     * ===================================================== */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
