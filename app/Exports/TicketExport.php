<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Kategori',
            'Prioritas',
            'Lokasi',
            'Judul',
            'Status',
            'Tanggal Dibuat',
            'Tanggal Selesai',
        ];
    }

    public function array(): array
    {
        return Ticket::select(
            'id',
            'user_id',
            'category',
            'priority',
            'room_id',
            'title',
            'status',
            'created_at',
            'closed_at'
        )->get()->toArray();
    }
}
