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
        $owner = null; // Inisialisasi variabel owner

        if ($kamarDitempati && $kamarDitempati->kos) {
            $kos = $kamarDitempati->kos;
            
            // 1. Ambil data User Pemilik (Owner) dari Kos
            // Asumsi: Model Kos memiliki relasi 'user' atau 'owner' yang mengarah ke User model
            // Jika model Kos Anda memiliki kolom 'user_id' yang menunjuk ke Pemilik:
            $owner = $kos->user; // Asumsi: $kos->user adalah relasi di model Kos yang mengarah ke pemilik (User)
            
            // Atau jika ID pemilik ada di Kos model:
            // $owner = User::find($kos->id_owner_kolom); // Ganti id_owner_kolom dengan nama kolom yang benar

            $kosCollection = collect([$kos]);
        }

        // Kirim semua variabel yang diperlukan ke view
        return view('dashboard.penghuni', compact('kosCollection', 'kamarDitempati', 'owner'));
    }


    public function profil()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();
        $kamarDitempati = Kamar::where('user_id', $user->id)->first();

        // Tentukan status berdasarkan keberadaan kamar
        $statusAktif = $kamarDitempati ? 'Aktif' : 'Tidak Aktif';
        return view('profile.profilpenghuni', compact('user', 'statusAktif', 'kamarDitempati'));
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
            if ($user->foto_profile) { 
                Storage::delete($user->foto_profile); 
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
}
