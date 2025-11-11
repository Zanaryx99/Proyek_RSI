<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// PASTIKAN BARIS INI ADA:
use App\Models\Pembayaran; 
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function store(Request $request)
{
    // Validasi yang KETAT
    $validatedData = $request->validate([
        'metode_pembayaran'   => 'required|string|max:100',
        'nominal'             => 'required|integer|min:1000',
        'bukti_pembayaran_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
    ]);

    $filePath = null;
    if ($request->hasFile('bukti_pembayaran_file')) {
        $file = $request->file('bukti_pembayaran_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        // Penyimpanan file ke storage/app/public/bukti-pembayaran
        $filePath = $file->storeAs('bukti-pembayaran', $fileName, 'public'); 
    }

    Pembayaran::create([
        'nominal'           => $validatedData['nominal'],
        'tanggal_bayar'     => now(), 
        'metode_pembayaran' => $validatedData['metode_pembayaran'],
        'bukti_pembayaran'  => $filePath, 
    ]);

    // REDIRECT HARUS KE HALAMAN YANG BISA DILIHAT (GET), Yaitu halaman Anda saat ini (/penghuni)
    return redirect('/Dpenghuni')->with('success', 'Bukti pembayaran berhasil diunggah.');
}
}