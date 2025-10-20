<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    // Dashboard penghuni
    public function dashboardPenghuni()
    {
        return view('dashboard.penghuni'); // buat file: resources/views/dashboard/penghuni.blade.php
    }

    // Dashboard pemilik
    public function dashboardPemilik()
    {
        return view('dashboard.pemilik'); // buat file: resources/views/dashboard/pemilik.blade.php
    }
}
