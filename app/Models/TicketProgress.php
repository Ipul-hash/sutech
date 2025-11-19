<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'updated_by', 'progress'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
