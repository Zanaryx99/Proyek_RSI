<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        // Muat relasi user (pengirim) agar data nama pengirim tersedia di frontend
        $this->message = $message->load('user'); 
    }

    /**
     * Tentukan channel yang akan menerima broadcast.
     * Pesan harus diterima oleh PENGIRIM (untuk konfirmasi) dan PENERIMA (pesan baru).
     */
    public function broadcastOn(): array
    {
        // Channel untuk Pengirim (user_id)
        $senderChannel = new PrivateChannel('chat.' . $this->message->user_id);
        
        // Channel untuk Penerima (recipient_id)
        $recipientChannel = new PrivateChannel('chat.' . $this->message->recipient_id);

        return [
            $senderChannel,
            $recipientChannel,
        ];
    }
    
    // Tentukan data yang dikirim melalui broadcast
    public function broadcastWith(): array
    {
        return [
            // Kirim Model Message yang sudah dimuat relasinya
            'message' => $this->message, 
        ];
    }
}
