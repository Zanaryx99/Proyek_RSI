<div 
    {{-- Pastikan semua kode Livewire Anda HANYA ada di dalam DIV ini. --}}
    class="flex flex-col h-full bg-white rounded-lg shadow-xl max-w-6xl mx-auto my-10 border border-gray-100"
    
    {{-- Alpine.js untuk Auto-Scroll --}}
    x-data="{ messagesCount: @entangle('messagesCount'), init() { this.$nextTick(() => this.scrollToBottom()); } }"
    x-on:messages-updated.window="scrollToBottom()"
>

    {{-- 1. Header Chat (Top Bar) --}}
<div class="p-4 border-b flex items-center justify-between sticky top-0 bg-white z-10 rounded-t-lg">
    {{-- LOGIKA BARU UNTUK FOTO PROFIL --}}
    @php
        // Ambil nama dari kolom 'nama_lengkap'
        $userName = $user->nama_lengkap ?? $user->username ?? 'Pengguna'; 
        
        // Cek kolom foto_profile, jika ada, gunakan asset storage. Jika tidak, gunakan UI Avatars.
        $foto_profile_url = $user->foto_profile 
                          ? asset('storage/' . $user->foto_profile) 
                          // Fallback ke UI Avatars menggunakan nama lengkap/username
                          : 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&color=7F9CF5&background=EBF4FF';
    @endphp
    
    {{-- Grup Elemen Kiri: Tombol Kembali + Foto Profil + Nama --}}
    <div class="flex items-center gap-3">
        {{-- Tombol Kembali --}}
        <a href="#" onclick="window.history.back()" class="text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        
        {{-- FOTO PROFIL (Sekarang di pojok kiri setelah tombol kembali) --}}
        <img class="w-10 h-10 rounded-full object-cover border border-gray-200" 
            src="{{ $foto_profile_url }}" 
            alt="Foto Profil {{ $userName }}"
            title="{{ $userName }}"
        >

        {{-- NAMA PENGGUNA --}}
        <h3 class="text-lg font-semibold text-gray-800">
            {{ $userName }}
        </h3>
    </div>
    
    {{-- Elemen Kosong di Kanan untuk menjaga layout 'justify-between' --}}
    <div class="w-6 h-6"></div> 
</div>

    {{-- 2. Daftar Pesan --}}
    {{-- wire:poll.1000ms adalah 1 detik --}}
    {{-- Tambahkan x-ref untuk diakses Alpine.js --}}
    <div id="chat-container" class="space-y-4 p-4 overflow-y-auto grow h-[650px]" wire:poll.1000ms x-ref="chatContainer" x-init="$watch('messagesCount', () => scrollToBottom())">
        @foreach ($messages as $message)
            @php
                $isSender = $message->from_user_id === Auth::id();
                $justifyClass = $isSender ? 'justify-end' : 'justify-start';
                // Menggunakan skema warna Tailwind yang lebih kontemporer
                $bubbleBg = $isSender ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800'; 
            @endphp

            <div class="flex {{ $justifyClass }}">
                <div class="flex flex-col max-w-xs sm:max-w-md">
                    
                    {{-- Bubble Pesan --}}
                    <div class="p-3 text-sm rounded-lg shadow-sm {{ $bubbleBg }} 
                        {{ $isSender ? 'rounded-br-none' : 'rounded-tl-none' }}
                    ">
                        {{ $message->message }}
                    </div>
                    
                    {{-- Footer Pesan (Waktu dan Nama) --}}
                    <div class="mt-1 text-xs text-gray-500 {{ $isSender ? 'text-right' : 'text-left' }}">
                        @if (!$isSender)
                            <span class="font-semibold">{{ $message->fromUser->name }}</span>
                            <time class="ml-1">{{ $message->created_at->diffForHumans() }}</time>
                        @else
                            <time>{{ $message->created_at->diffForHumans() }}</time>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    {{-- 3. Form Input --}}
    <form wire:submit.prevent="sendMessage" class="p-4 border-t shrink-0">
        <div class="flex items-end gap-2">
            <textarea 
                {{-- Menggunakan .defer agar tidak memicu update saat mengetik (hanya saat submit) --}}
                wire:model.defer="message" 
                class="textarea h-16 w-full resize-none border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                placeholder="Ketik pesan Anda..."
                required
            ></textarea>
            <button 
                type="submit" 
                class="h-16 py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shrink-0" 
                {{-- Menonaktifkan tombol jika pesan kosong --}}
                @if (empty($message)) disabled @endif
            >
                Kirim
            </button>
        </div>
    </form>
    
    {{-- Script Alpine.js untuk Auto Scroll --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatComponent', () => ({
                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                }
            }));
        });
    </script>
</div>