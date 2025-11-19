<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function tiketUser(){
        return view('user.user-tiket');
    }

    public function dashboardUser(){
        return view('user.user-dashboard');
    }
}
