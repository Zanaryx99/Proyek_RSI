<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function edit(Kos $kos)
    {
        // Validasi user - hanya pemilik yang bisa edit
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pendaftaran.kos', compact('kos'));
    }

    public function update(Request $request, Kos $kos)
    {
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis' => 'required|in:Pria,Wanita,Campur',
            'lokasi' => 'required|string',
            'fasilitas_umum' => 'required|string',
            'peraturan_umum' => 'required|string',
        ]);

        if ($request->hasFile('foto')) {
            if ($kos->foto && Storage::disk('public')->exists($kos->foto)) {
                Storage::disk('public')->delete($kos->foto);
            }

            $path = $request->file('foto')->store('foto-kos', 'public');
            $validated['foto'] = $path;
        }

        $kos->update($validated);

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Kos berhasil dihapus!');
    }



    public function destroy(Kos $kos)
    {
        // Validasi user
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus foto dari storage jika ada
        if ($kos->foto && Storage::disk('public')->exists($kos->foto)) {
            Storage::disk('public')->delete($kos->foto);
        }

        $kos->delete();

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Kos berhasil dihapus!');
    }

    public function indexSK()
    {
        $kosCollection = Kos::where('user_id', Auth::id())
            ->with('user')
            ->latest()
            ->get();

        return view('dashboard.pemilik', compact('kosCollection'));
    }

    public function kontrolKos(Kos $kos)
    {
        // pastikan hanya pemilik kos yang dapat mengakses
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        return view('dashboard.KontrolKos', compact('kos'));
    }
}
