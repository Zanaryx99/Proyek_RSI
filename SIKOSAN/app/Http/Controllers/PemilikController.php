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
        // Verifikasi Keamanan
        if ($kos->user_id !== Auth::id()) {
            abort(403);
        }

        // Ambil semua data terkait dengan efisien
        $semuaKamar = Kamar::where('kos_id', $kos->id)->with('user')->get();

        // 1. Filter kamar yang dihuni (ini adalah data yang kita butuhkan)
        $kamarDihuni = $semuaKamar->where('status', 'terisi')->whereNotNull('user_id');

        // 2. Hitung total pemasukan langsung dari kamar yang dihuni
        $totalPemasukan = $kamarDihuni->sum('harga_sewa');

        // Kalkulasi lain untuk ringkasan (jika masih diperlukan)
        $avgHarga = $semuaKamar->isNotEmpty() ? round($semuaKamar->avg('harga_sewa')) : null;
        $minMinimal = $semuaKamar->isNotEmpty() ? $semuaKamar->min('minimal_waktu_sewa') : null;
        $jumlahPenghuni = $kamarDihuni->count();
        $rating = $kos->rating ?? null;

        // Kumpulkan daftar review dari kamar yang memiliki rating (asumsi rating & review disimpan di model Kamar)
        $reviews = $kamarDihuni->filter(function ($k) {
            return !is_null($k->rating) && trim((string)($k->rating)) !== '';
        })->map(function ($k) {
            return [
                'user' => $k->user, // relasi user (bisa null kalau data tak konsisten)
                'nama_kamar' => $k->nama_kamar,
                'rating' => $k->rating,
                'review' => $k->review,
                'user_id' => $k->user_id,
            ];
        })->values();

        // Hitung rata-rata rating berdasarkan review yang ada (bulatkan ke 0.5 terdekat untuk tampilan)
        $avgRating = $reviews->isNotEmpty() ? round($reviews->avg('rating') * 2) / 2 : null;

        // Kirim data yang benar ke View
        return view('dashboard.KontrolKos', compact(
            'kos',
            'kamarDihuni',      // <-- Variabel utama untuk tabel rincian
            'totalPemasukan',   // <-- Variabel untuk total di footer
            'avgHarga',
            'minMinimal',
            'jumlahPenghuni',
            'rating',
            'reviews',
            'avgRating'
        ));
    }

    // ==================== METHOD UNTUK Profil ====================

    public function profil()
    {
        $user = Auth::user();

        // Validasi role
        if ($user->peran !== 'Pemilik') {
            abort(403, 'Unauthorized access');
        }

        // Eager loading untuk optimasi query
        $kosCollection = Kos::where('user_id', $user->id)
            ->latest()
            ->get();

        // Hitung statistik
        $statistics = [
            'total_kos' => $kosCollection->count(),
        ];

        return view('profile.profilpemilik', compact('user', 'kosCollection', 'statistics'));
    }

    public function update_profil(Request $request)
    {
        $user = Auth::user(); // Perbaiki: Auth::users() -> Auth::user()

        // Validasi role
        if ($user->peran !== 'Pemilik') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'no_telepon.regex' => 'Format nomor telepon tidak valid',
            'foto_profile.max' => 'Ukuran foto maksimal 2MB'
        ]);

        try {
            if ($request->hasFile('foto_profile')) {
                // Hapus foto lama jika ada
                if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
                    Storage::disk('public')->delete($user->foto_profile);
                }

                // Simpan foto baru
                $path = $request->file('foto_profile')->store('profiles', 'public');
                $validated['foto_profile'] = $path;
            }

            $user->update($validated);

            return redirect()->back()->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil');
        }
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
            ->with(['kos', 'user']) // Include relasi user
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
            'status' => 'nullable|in:tersedia,terisi,renovasi', // Tambah validasi status
        ]);

        // Format harga sewa (hapus titik pemisah ribuan)
        $validated['harga_sewa'] = str_replace('.', '', $validated['harga_sewa']);

        // Upload foto jika ada
        if ($request->hasFile('foto_kamar')) {
            $path = $request->file('foto_kamar')->store('foto-kamar', 'public');
            $validated['foto_kamar'] = $path;
        }

        // Tambahkan data tambahan
        $validated['kos_id'] = $kos->id;
        $validated['status'] = $validated['status'] ?? 'tersedia'; // Default status
        // user_id tetap null karena kamar baru belum ada penghuni
        // kode_unik akan otomatis digenerate oleh model

        Kamar::create($validated);

        return redirect()->route('kamar.index', $kos->id)->with('success', 'Kamar berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit kamar
     */
    public function editKamar(Kamar $kamar)
    {
        // Perbaiki authorization
        if ($kamar->kos->user_id !== Auth::id()) {
            abort(403);
        }

        // Tidak perlu query kos lagi, karena sudah ada relasi
        $kos = $kamar->kos;

        return view('pendaftaran.daftarkamar', compact('kamar', 'kos'));
    }

    /**
     * Update data kamar
     */
    public function updateKamar(Request $request, Kamar $kamar)
    {
        // Perbaiki authorization
        if ($kamar->kos->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kamar' => 'required|string|max:255',
            'harga_sewa' => 'required|string|max:255',
            'minimal_waktu_sewa' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_kamar' => 'nullable|string|max:255',
            'foto_kamar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:tersedia,terisi,renovasi', // Wajib untuk update
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

        // Jika status diubah menjadi 'tersedia', set user_id menjadi null
        if ($validated['status'] === 'tersedia') {
            $validated['user_id'] = null;
        }

        $kamar->update($validated);

        return redirect()->route('kamar.index', $kamar->kos_id)->with('success', 'Kamar berhasil diperbarui!');
    }

    /**
     * Menghapus kamar
     */
    public function destroyKamar(Kamar $kamar)
    {
        // Perbaiki authorization
        if ($kamar->kos->user_id !== Auth::id()) {
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
     * Update status kamar saja
     */
    public function updateStatusKamar(Request $request, Kamar $kamar)
    {
        if ($kamar->kos->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:tersedia,terisi,renovasi',
        ]);

        $updateData = ['status' => $request->status];

        // Jika status diubah menjadi 'tersedia', set user_id menjadi null
        if ($request->status === 'tersedia') {
            $updateData['user_id'] = null;
        }

        $kamar->update($updateData);

        return back()->with('success', 'Status kamar berhasil diubah!');
    }

    /**
     * Menampilkan detail kamar
     */
    public function showKamar(Kamar $kamar)
    {
        if ($kamar->kos->user_id !== Auth::id()) {
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
