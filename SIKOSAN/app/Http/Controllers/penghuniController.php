<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Get kos data if user has id_kos
        $kosCollection = collect();
        if ($user->id_kos) {
            $kos = Kos::find($user->id_kos);
            if ($kos) {
                $kosCollection = collect([$kos]);
            }
        }

        return view('dashboard.penghuni', compact('kosCollection'));
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
}
