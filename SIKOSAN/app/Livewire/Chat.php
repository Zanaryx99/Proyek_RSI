<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    // Properti ini otomatis diisi dari routing: /chat/{user}
    public User $user;
    
    // Properti untuk menyimpan input pesan, menggunakan .defer
    public string $message = '';

    // Digunakan untuk auto-scroll (akan dijelaskan di bagian View)
    public $messagesCount;

    // Digunakan untuk menangkap parameter dari route
    public function mount(User $user)
    {
        $this->user = $user;
        // Inisialisasi hitungan pesan saat pertama kali dimuat
        $this->messagesCount = $this->getMessagesQuery()->count();
    }
    
    // Metode untuk query pesan (dibuat terpisah agar bisa dipakai di render dan mount)
    private function getMessagesQuery()
    {
        $currentUserId = Auth::id();
        $targetUserId = $this->user->id;

        return Message::query()
            // Ambil pesan dari A ke B ATAU dari B ke A
            ->where(function ($query) use ($currentUserId, $targetUserId) {
                $query->where('from_user_id', $currentUserId)
                      ->where('to_user_id', $targetUserId);
            })
            ->orWhere(function ($query) use ($currentUserId, $targetUserId) {
                $query->where('from_user_id', $targetUserId)
                      ->where('to_user_id', $currentUserId);
            })
            ->with('fromUser')
            ->orderBy('created_at', 'asc');
    }

    public function sendMessage()
    {
        // Pastikan pesan tidak kosong sebelum menyimpan
        if (empty(trim($this->message))) {
            return;
        }

        // 1. Simpan pesan ke database
        Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $this->user->id,
            'message' => $this->message,
        ]);
        
        // 2. Reset input pesan
        $this->reset('message');

        // 3. Update hitungan pesan untuk memicu auto-scroll
        $this->messagesCount = $this->getMessagesQuery()->count();
    }

    public function render()
    {
        // Ambil pesan menggunakan query yang sudah didefinisikan
        $messages = $this->getMessagesQuery()->get();

        return view('livewire.chat', [
            'messages' => $messages,
        ]);
    }
}