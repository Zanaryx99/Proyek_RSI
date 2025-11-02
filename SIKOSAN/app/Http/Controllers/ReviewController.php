<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        try {
            // Dapatkan kamar yang ditempati oleh penghuni yang sedang login
            $kamar = Kamar::where('user_id', Auth::id())
                        ->where('status', 'Terisi')
                        ->first();

            if (!$kamar) {
                return redirect()->back()
                    ->with('error', 'Anda harus menjadi penghuni aktif untuk memberikan review.');
            }

            // Cek apakah sudah pernah review
            if ($kamar->review !== null) {
                return redirect()->back()
                    ->with('error', 'Anda sudah memberikan review sebelumnya. Silakan edit review yang ada.');
            }

            // Update kamar dengan review baru
            $kamar->update([
                'rating' => $request->rating,
                'review' => $request->review,
                'reviewed_at' => Carbon::now(),
            ]);

            return redirect()->back()
                ->with('success', 'Review berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan review.');
        }
    }

    /**
     * Update existing review
     */
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        try {
            // Dapatkan kamar yang ditempati oleh penghuni yang sedang login
            $kamar = Kamar::where('user_id', Auth::id())
                        ->where('status', 'Terisi')
                        ->first();

            if (!$kamar) {
                return redirect()->back()
                    ->with('error', 'Anda harus menjadi penghuni aktif untuk mengubah review.');
            }

            // Pastikan kamar memiliki review yang bisa diupdate
            if ($kamar->review === null) {
                return redirect()->back()
                    ->with('error', 'Tidak ada review yang dapat diubah.');
            }

            // Update review
            $kamar->update([
                'rating' => $request->rating,
                'review' => $request->review,
                'reviewed_at' => Carbon::now(),
            ]);

            return redirect()->back()
                ->with('success', 'Review berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui review.');
        }
    }

    /**
     * Get reviews for a specific kos
     */
    public function getKosReviews($kosId)
    {
        try {
            // Ambil semua kamar di kos tersebut yang memiliki review
            $reviews = Kamar::where('kos_id', $kosId)
                        ->whereNotNull('review')
                          ->with(['user:id,nama_lengkap,foto_profile']) // Ambil data user yang memberi review
                          ->orderBy('reviewed_at', 'desc') // Urutkan dari yang terbaru
                        ->get();

            // Hitung rata-rata rating
            $averageRating = $reviews->avg('rating');
            
            return response()->json([
                'reviews' => $reviews,
                'averageRating' => round($averageRating, 1), // Bulatkan ke 1 desimal
                'totalReviews' => $reviews->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data review.'
            ], 500);
        }
    }

    /**
     * Delete a review (opsional)
     */
    public function destroy()
    {
        try {
            // Dapatkan kamar yang ditempati oleh penghuni yang sedang login
            $kamar = Kamar::where('user_id', Auth::id())
                        ->where('status', 'Terisi')
                        ->first();

            if (!$kamar) {
                return redirect()->back()
                    ->with('error', 'Anda harus menjadi penghuni aktif untuk menghapus review.');
            }

            // Reset kolom review
            $kamar->update([
                'rating' => null,
                'review' => null,
                'reviewed_at' => null
            ]);

            return redirect()->back()
                ->with('success', 'Review berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus review.');
        }
    }
}