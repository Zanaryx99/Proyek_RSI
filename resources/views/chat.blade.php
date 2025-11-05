<div>
    {{-- Bagian List Chat (Tambahkan wire:poll di sini untuk Real-Time) --}}
    <div class="space-y-4 p-4 max-h-96 overflow-y-auto" wire:poll>
        @foreach ($messages as $message)
            @php
                // Tentukan apakah pesan dikirim oleh user yang sedang login
                $isSender = $message->from_user_id === Auth::id();
            @endphp

            {{-- Gunakan kelas chat-end (kanan) untuk pengirim dan chat-start (kiri) untuk penerima --}}
            <div class="chat {{ $isSender ? 'chat-end' : 'chat-start' }}">
                <div class="chat-header">
                    {{ $message->fromUser->name }} {{-- Relasi fromUser harus dibuat di Model Message --}}
                    <time class="text-xs opacity-50">{{ $message->created_at->diffForHumans() }}</time>
                </div>
                <div class="chat-bubble {{ $isSender ? 'chat-bubble-primary' : '' }}">
                    {{ $message->message }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Bagian Form Input Pesan --}}
    <form wire:submit.prevent="sendMessage" class="p-4 border-t">
        <div class="form-control">
            <textarea 
                wire:model.defer="message" 
                class="textarea textarea-bordered h-24" 
                placeholder="Ketik pesan Anda..."
            ></textarea>
            <button type="submit" class="btn btn-primary mt-2" @if (!$message) disabled @endif>Kirim</button>
        </div>
    </form>
</div>