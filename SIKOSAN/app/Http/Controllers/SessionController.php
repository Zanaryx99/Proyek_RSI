<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SessionController extends Controller
{
    public function index()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->peran === 'Pemilik') {
                return redirect('/Dpemilik');
            } elseif ($user->peran === 'Penghuni') {
                return redirect('/DPenghuni');
            } else {
                Auth::logout();
                return redirect('/login')->with('error', 'Peran tidak valid.');
            }
        }

        return redirect('/login')->with('error', 'Email atau password salah.');
    }

    public function register()
    {
        return view('Authregister');
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'peran' => 'required|in:Pemilik,Penghuni',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'peran' => $request->peran,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
