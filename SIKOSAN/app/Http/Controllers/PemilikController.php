<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PemilikController extends Controller
{
    // ==================== METHOD UNTUK KOS ====================

    /**
     * Menampilkan form pendaftaran kos
     */
    public function create()
    {
        return view('pendaftaran.kos');
    }

    /**
     * Menyimpan data kos baru
     */
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

        return redirect()->route('pemilik.dashboard')->with('success', 'Kos berhasil didaftarkan!');
    }

    /**
     * Menampilkan form edit kos
     */
    public function edit(Kos $kos)
    {
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pendaftaran.kos', compact('kos'));
    }

    /**
     * Update data kos
     */
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
            ->with('success', 'Kos berhasil diupdate!');
    }

    /**
     * Menghapus kos
     */
    public function destroy(Kos $kos)
    {
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

    /**
     * Menampilkan dashboard pemilik dengan daftar kos
     */
    public function indexSK()
    {
        $kosCollection = Kos::where('user_id', Auth::id())
            ->with('user')
            ->latest()
            ->get();

        return view('dashboard.pemilik', compact('kosCollection'));
    }

    /**
     * Menampilkan halaman kontrol kos (detail kos)
     */
    public function kontrolKos(Kos $kos)
    {
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        return view('dashboard.KontrolKos', compact('kos'));
    }

    // ==================== METHOD UNTUK KAMAR ====================

    /**
     * Menampilkan daftar kamar berdasarkan kos
     */
    public function indexKamar(Kos $kos)
    {
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        $kamar = Kamar::where('kos_id', $kos->id)
            ->with('kos')
            ->latest()
            ->get();

        return view('dashboard.kamar', compact('kamar', 'kos'));
    }

    /**
     * Menampilkan form pendaftaran kamar
     */
    public function createKamarForm(Kos $kos)
    {
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pendaftaran.daftarkamar', compact('kos'));
    }

    /**
     * Menyimpan data kamar baru
     */
    public function storeKamar(Request $request, Kos $kos)
    {
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kamar' => 'required|string|max:255',
            'harga_sewa' => 'required|string|max:255',
            'minimal_waktu_sewa' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_kamar' => 'nullable|string|max:255',
            'foto_kamar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Format harga sewa (hapus titik pemisah ribuan)
        $validated['harga_sewa'] = str_replace('.', '', $validated['harga_sewa']);

        // Upload foto jika ada
        if ($request->hasFile('foto_kamar')) {
            $path = $request->file('foto_kamar')->store('foto-kamar', 'public');
            $validated['foto_kamar'] = $path;
        }

        // Tambahkan user_id dan kos_id
        $validated['user_id'] = Auth::id();
        $validated['kos_id'] = $kos->id;

        Kamar::create($validated);

        // Redirect kembali ke daftar kamar kos tersebut
        return redirect()->route('kamar.index', $kos->id)->with('success', 'Kamar berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit kamar
     */
    public function editKamar(Kamar $kamar)
{
    if ($kamar->user_id !== Auth::id()) {
        abort(403);
    }

    // MEMUAT OBJEK KOS TERKAIT
    $kos = Kos::findOrFail($kamar->kos_id);

    // MENGIRIM KEDUA VARIABEL: $kamar dan $kos
    return view('pendaftaran.daftarkamar', compact('kamar', 'kos'));
}
    /**
     * Update data kamar
     */
    public function updateKamar(Request $request, Kamar $kamar)
    {
        if ($kamar->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kamar' => 'required|string|max:255',
            'harga_sewa' => 'required|string|max:255',
            'minimal_waktu_sewa' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_kamar' => 'nullable|string|max:255',
            'foto_kamar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Format harga sewa (hapus titik pemisah ribuan)
        $validated['harga_sewa'] = str_replace('.', '', $validated['harga_sewa']);

        // Upload foto baru jika ada
        if ($request->hasFile('foto_kamar')) {
            // Hapus foto lama jika ada
            if ($kamar->foto_kamar && Storage::disk('public')->exists($kamar->foto_kamar)) {
                Storage::disk('public')->delete($kamar->foto_kamar);
            }

            $path = $request->file('foto_kamar')->store('foto-kamar', 'public');
            $validated['foto_kamar'] = $path;
        }

        $kamar->update($validated);

        return redirect()->route('kamar.index', $kamar->kos_id)->with('success', 'Kamar berhasil diperbarui!');
    }

    /**
     * Menghapus kamar
     */
    public function destroyKamar(Kamar $kamar)
    {
        if ($kamar->user_id !== Auth::id()) {
            abort(403);
        }

        $kos_id = $kamar->kos_id;

        // Hapus foto dari storage jika ada
        if ($kamar->foto_kamar && Storage::disk('public')->exists($kamar->foto_kamar)) {
            Storage::disk('public')->delete($kamar->foto_kamar);
        }

        $kamar->delete();

        return redirect()->route('kamar.index', $kos_id)->with('success', 'Kamar berhasil dihapus!');
    }

    /**
     * Menampilkan detail kamar
     */
    public function showKamar(Kamar $kamar)
    {
        if ($kamar->user_id !== Auth::id()) {
            abort(403);
        }

        return view('kamar.show', compact('kamar'));
    }

    /**
     * Menampilkan halaman pilih kos untuk menambah kamar (opsional)
     */
    public function pilihKosUntukKamar()
    {
        $kosCollection = Kos::where('user_id', Auth::id())->get();
        
        if ($kosCollection->isEmpty()) {
            return redirect()->route('kos.create')
                ->with('warning', 'Anda harus memiliki kos terlebih dahulu sebelum menambahkan kamar.');
        }

        return view('kamar.pilih-kos', compact('kosCollection'));
    }
}