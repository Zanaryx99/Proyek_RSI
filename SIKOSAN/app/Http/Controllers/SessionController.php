<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    public function index()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'Email atau Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $hasKos = User::where('id', $user->id)
                ->whereHas('kos')
                ->exists();

            if ($user->peran === 'Pemilik') {

                if ($hasKos) {

                    return redirect('/kos');
                } else {
                    return redirect('/Dpemilik');
                }
                return redirect('/Dpemilik');
            } elseif ($user->peran === 'Penghuni') {
                return redirect('/DPenghuni');
            } else {
                Auth::logout();
                return redirect('/login')->with('error', 'Peran tidak valid.');
            }
        }

        return redirect('/login')->with('error', 'Kredensial yang dimasukkan tidak valid.');
    }

    public function register()
    {
        return view('Auth.register');
    }

    public function create(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => $request->peran,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
