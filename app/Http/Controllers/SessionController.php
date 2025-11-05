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
        $credentials = $request->validate([
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
                // Cek apakah punya kos
                $hasKos = User::where('id', $user->id)
                    ->whereHas('kos')
                    ->exists();

                return $hasKos ? redirect('/kos') : redirect('/Dpemilik');
            }

            if ($user->peran === 'Penghuni') {
                return redirect('/Dpenghuni');
            }

            Auth::logout();
            return redirect('/login')->with('error', 'Peran tidak valid.');
        }

        return back()
            ->withInput()  // Tambahkan ini untuk mempertahankan input lama
            ->with('error', 'Email/Username atau Password salah.');
    }


    public function register()
    {
        return view('Auth.register');
    }

    public function create(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
                // Username errors
                'username.required' => 'Username wajib diisi',
                'username.unique' => 'Username ini sudah digunakan, silakan pilih username lain',

                // Email errors
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain',

                // Password errors
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal harus 6 karakter',
                'password_confirmation.required' => 'Konfirmasi password wajib diisi',
                'password_confirmation.same' => 'Konfirmasi password tidak sama dengan password'
            ]
        );

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => $request->peran,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
