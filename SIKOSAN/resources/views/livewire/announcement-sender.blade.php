{{-- Menggunakan style kartu yang sama dengan dasbor penghuni --}}
<div class="p-6 bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-gray-200">

    {{-- Header Kartu (Diubah ke Teal) --}}
    <h4 class="text-2xl font-bold text-teal-800 mb-5 flex items-center">
        <i class='bx bxs-broadcast text-3xl mr-3 text-teal-600'></i>
        Kirim Pengumuman Kos
    </h4>

    {{-- Pesan Sukses (Tetap Hijau) --}}
    @if (session()->has('announcement-success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-4" role="alert">
        <p class="font-bold">Pengiriman Sukses!</p>
        <p>{{ session('announcement-success') }}</p>
    </div>
    @endif

    {{-- Pesan Error (Tetap Merah) --}}
    @if (session()->has('announcement-error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-4" role="alert">
        <p class="font-bold">Pengiriman Gagal!</p>
        <p>{{ session('announcement-error') }}</p>
    </div>
    @endif

    <form wire:submit.prevent="sendAnnouncement">
        <div class="mb-5">
            <label for="announcementMessage" class="block text-sm font-semibold text-gray-800 mb-2">Pesan Pengumuman</label>
            <textarea
                wire:model.defer="announcementMessage"
                rows="6"
                id="announcementMessage"
                {{-- Fokus diubah ke Teal --}}
                class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full text-base border-gray-300 rounded-lg p-3 border resize-none transition duration-150 ease-in-out @error('announcementMessage') border-red-500 bg-red-50 @enderror"
                placeholder="Tulis pesan penting yang akan dikirim ke semua penghuni kos Anda..."></textarea>

            {{-- Error validasi inline (Tetap Merah) --}}
            @error('announcementMessage')
            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol diubah dari Merah ke Teal, menggunakan kelas yang selaras --}}
        <button
            type="submit"
            class="w-full inline-flex items-center justify-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-3 focus:ring-offset-2 focus:ring-teal-500 transition duration-300 ease-in-out shadow-lg shadow-teal-100 disabled:bg-teal-400"
            wire:loading.attr="disabled"
            wire:target="sendAnnouncement">
            {{-- Teks tombol saat tidak loading --}}
            <span wire:loading.remove wire:target="sendAnnouncement">
                Kirim Pengumuman Sekarang
            </span>

            {{-- Teks dan ikon saat sedang loading (tetap dipertahankan) --}}
            <span wire:loading wire:target="sendAnnouncement">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sedang Mengirim Massal...
            </span>
        </button>
    </form>
</div>