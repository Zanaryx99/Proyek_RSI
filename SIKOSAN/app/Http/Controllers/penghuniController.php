<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\User;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class penghuniController extends Controller
{

    public function indexPi()
    {
        $user = Auth::user();

        // Verify user is a Penghuni
        if ($user->peran !== 'Penghuni') {
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Anda bukan penghuni.');
        }

        // Get kamar yang sedang ditempati oleh user ini
        $kamarDitempati = Kamar::where('user_id', $user->id)->first();

        $kosCollection = collect();

        if ($kamarDitempati && $kamarDitempati->kos) {
            $kosCollection = collect([$kamarDitempati->kos]);
        }

        return view('dashboard.penghuni', compact('kosCollection', 'kamarDitempati'));
    }


    public function profil()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        return view('profile.profilpenghuni', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_telepon' => 'nullable|numeric',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // Handle upload foto profil
        if ($request->hasFile('foto_profile')) {
            // Hapus foto lama jika ada
            if ($user->foto_profile) {  // ← PERBAIKI: ganti 'profile' menjadi 'foto_profile'
                Storage::delete($user->foto_profile);  // ← PERBAIKI: ganti 'profile' menjadi 'foto_profile'
            }

            // Simpan foto baru
            $path = $request->file('foto_profile')->store('profiles', 'public');
            $validated['foto_profile'] = $path;
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function daftarKamar(Request $request)
    {
        $request->validate([
            'kode_unik' => 'required|string|exists:kamar,kode_unik'
        ]);

        // Cari kamar berdasarkan kode unik
        $kamar = Kamar::where('kode_unik', $request->kode_unik)->first();

        // Cek status kamar
        if ($kamar->isTerisi()) {
            return back()->withErrors([
                'kode_unik' => 'Kamar ini sudah ditempati oleh penghuni lain.'
            ]);
        }

        if ($kamar->isRenovasi()) {
            return back()->withErrors([
                'kode_unik' => 'Kamar sedang dalam perbaikan .'
            ]);
        }

        if (!$kamar->isTersedia()) {
            return back()->withErrors([
                'kode_unik' => 'Kamar tidak tersedia untuk ditempati.'
            ]);
        }

        // Assign user_id dan ubah status ke terisi
        $kamar->update([
            'user_id' => Auth::id(),
            'status' => 'terisi'
        ]);

        return redirect()->route('penghuni.dashboard')
            ->with('success', 'Selamat! Anda berhasil mendaftar di kamar ' . $kamar->nama_kamar);
    }

    // Method untuk penghuni keluar dari kamar
    public function keluarKamar($id)
    {
        $kamar = Kamar::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Kembalikan status ke tersedia
        $kamar->update([
            'user_id' => null,
            'status' => 'tersedia'
        ]);

        return redirect()->route('penghuni.dashboard')
            ->with('success', 'Anda berhasil keluar dari kamar ' . $kamar->nama_kamar);
    }
    // public function joinKos(Request $request)
    // {
    //     $request->validate([
    //         'id_kos' => 'required|exists:kos,id'
    //     ], [
    //         'id_kos.required' => 'ID Kos harus diisi',
    //         'id_kos.exists' => 'ID Kos tidak ditemukan'
    //     ]);

    //     $kos = Kos::find($request->id_kos);

    //     if (!$kos) {
    //         return back()
    //             ->withInput()
    //             ->withErrors(['id_kos' => 'Kos tidak ditemukan']);
    //     }

    //     // Update user's id_kos
    //     $user = Auth::user();
    //     $user->id_kos = $kos->id;
    //     $user->save();

    //     // Redirect to DPenghuni route instead of undefined penghuni.dashboard
    //     return redirect('/DPenghuni')
    //         ->with('success', 'Berhasil bergabung dengan kos!');
    // }
}