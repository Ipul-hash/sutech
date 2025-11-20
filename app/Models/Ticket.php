<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'assigned_to', 'category', 'priority',
        'room_id', 'position_id', 'title', 'description', 'root_cause',
        'assigned_team_id','status', 'sla_deadline', 'escalated_to', 'closed_at'
    ];

    protected $dates = ['sla_deadline', 'closed_at'];

    // Pelapor
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Teknisi yang menangani
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Eskalasi ke admin / manager
    public function escalatedTo()
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    public function progress()
    {
        return $this->hasMany(TicketProgress::class);
    }

    public function notes()
    {
        return $this->hasMany(TicketNote::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function history()
    {
        return $this->hasMany(TicketHistory::class);
    }

        // Relasi ke agent yang menangani tiket (assigned_to)
    public function agent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    
    public function position()
    {
        return $this->belongsTo(Position::class);
    }


}
