<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'created_by', 'note'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
