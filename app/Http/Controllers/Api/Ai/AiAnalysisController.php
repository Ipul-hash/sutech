<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiAnalysisController extends Controller
{
    public function user()
{
    $data = User::withCount('tickets')
        ->orderBy('tickets_count', 'desc')
        ->take(5)
        ->get();

    $text = "📊 *Top 5 User yang Paling Banyak Komplain:*\n\n";

    foreach ($data as $u) {
        $text .= "- {$u->name}: {$u->tickets_count} tiket\n";
    }

    return ['text' => nl2br($text)];
}

}
