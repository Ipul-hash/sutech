<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function dashboard()
    {
        return view('agent.dashboard');
    }

    public function tiket()
    {
        // Nanti bisa ambil tiket yang assign ke agent ini
        return view('agent.tiket');
    }

    public function profil()
    {
        return view('agent.profil', [
            'user' => auth()->user()
        ]);
    }
}