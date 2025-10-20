<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    function index()
    {
        return view('Auth/login');
    }

    function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => 'email wajib diisi',
                'password.required' => 'password wajib diisi',
            ]
        );

        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $infoLogin = [
            $loginField => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infoLogin)) {
            if (Auth::user()->role == 'penghuni') {
                return redirect('Dpemilik');
            } elseif (Auth::user()->role == 'pemilik') {
                return redirect('Dpemilik');
            }
        } else {
            return redirect()->back()->withErrors('Password dan Username salah')->withInput();
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect('login');
    }

    function register()
    {
        return view('Auth/register');
    }

    function create(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ],
            [
                'username.required' => 'Username wajib diisi',
                'username.unique' => 'Username sudah digunakan user lain',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Masukkan email yang valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 6 huruf',

            ]
        );

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        User::create($data);

        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
