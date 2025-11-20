<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketAttachment extends Model
{
    use HasFactory;

   protected $fillable = [
    'ticket_id',
    'file_path',
    'file_name',
    'mime_type',
    'file_size', // ✅ tambahin semua field yang disimpan
];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
