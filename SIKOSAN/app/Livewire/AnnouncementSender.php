<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use App\Models\Kos; // Wajib di-import
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnouncementSender extends Component
{
    // Properti input untuk pesan pengumuman
    public string $announcementMessage = '';

    // Method ini dipanggil saat form dikirim
    public function sendAnnouncement()
    {
        // 1. Validasi pesan
        $this->validate([
            'announcementMessage' => 'required|string|min:5',
        ]);

        $sender = Auth::user();
        
        // Cek Otorisasi (Contoh: hanya pemilik yang bisa kirim)
        if ($sender->peran !== 'Pemilik') {
             session()->flash('announcement-error', 'Anda tidak memiliki izin untuk mengirim pengumuman massal.');
             return;
        }

        // 1A. Cari kos PERTAMA yang dimiliki oleh pengguna saat ini
        $kos = $sender->kos()->first(); 
        
        if (!$kos) {
             session()->flash('announcement-error', 'Pengirim bukan pemilik Kos manapun yang terdaftar.');
             $this->reset('announcementMessage');
             return;
        }

        $kosId = $kos->id;
        
        // 2. Ambil SEMUA ID User yang menempati kamar di Kos ID ini (melalui tabel 'kamar').
        $recipients = DB::table('kamar')
                         ->where('kos_id', $kosId)
                         ->whereNotNull('user_id') // Hanya ambil kamar yang sudah terisi penghuni
                         ->pluck('user_id')
                         ->unique() // Menghindari duplikasi user_id
                         ->filter(fn ($id) => $id != $sender->id) // Hapus ID pengirim
                         ->toArray();

        if (empty($recipients)) {
            session()->flash('announcement-error', 'Tidak ada pengguna (penghuni) yang terdaftar di kos ini.');
            $this->reset('announcementMessage');
            return;
        }

        // 3. Persiapkan data untuk mass insertion
        $messagesToInsert = collect($recipients)->map(function ($recipientId) use ($sender) {
            return [
                'from_user_id' => $sender->id, 
                'to_user_id' => $recipientId,  
                'message' => $this->announcementMessage,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // 4. Lakukan mass insertion
        Message::insert($messagesToInsert);

        // 5. Feedback dan reset
        session()->flash('announcement-success', 'Pengumuman berhasil dikirim ke ' . count($recipients) . ' pengguna.');
        $this->reset('announcementMessage');
        
        // Kirim event untuk update tampilan chat lainnya
        $this->dispatch('announcementSent'); 
    }

    public function render()
    {
        return view('livewire.announcement-sender');
    }
}