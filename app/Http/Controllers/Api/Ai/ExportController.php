<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new TicketExport, 'laporan_tiket.xlsx');
    }
}
