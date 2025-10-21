<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{

    public function create()
    {
        return view('pendaftaran.kos');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis' => 'required|in:Pria,Wanita,Campur',
            'lokasi' => 'required|string',
            'fasilitas_umum' => 'required|string',
            'peraturan_umum' => 'required|string',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto-kos', 'public');
            $validated['foto'] = $path;
        }

        $validated['user_id'] = Auth::id();

        Kos::create($validated);

        return redirect()->route('kos.show')->with('success', 'Kos berhasil didaftarkan!');
    }

    public function indexGG()
    {
        return view('dashboard.pemilik');
    }

    public function indexSK()
    {
        $kosCollection = kos::with('user')->latest()->get();
        return view('dashboard.adapemilik', compact('kosCollection'));
    }
}
