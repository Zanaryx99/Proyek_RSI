<div class="p-5 bg-white rounded-xl shadow-2xl border border-red-200">
    <h4 class="text-2xl font-extrabold text-red-700 mb-5 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882L15.2 17.659a2 2 0 01-1.7 3.093H7.52c-.95 0-1.7-.753-1.7-1.688V6.2a2 2 0 011.7-1.688L11 3.312a2 2 0 012 0z" />
        </svg>
        Kirim Pengumuman Kos
    </h4>

    @if (session()->has('announcement-success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-4" role="alert">
            <p class="font-bold">Pengiriman Sukses!</p>
            <p>{{ session('announcement-success') }}</p>
        </div>
    @endif

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
                class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full text-base border-gray-300 rounded-lg p-3 border resize-none transition duration-150 ease-in-out @error('announcementMessage') border-red-500 bg-red-50 @enderror" 
                placeholder="Tulis pesan penting yang akan dikirim ke semua penghuni kos Anda..."
            ></textarea>
            @error('announcementMessage') 
                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> 
            @enderror
        </div>

        <button 
            type="submit" 
            class="w-full inline-flex items-center justify-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-3 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out shadow-lg shadow-red-200 disabled:bg-red-400"
            wire:loading.attr="disabled"
            wire:target="sendAnnouncement"
        >
            <span wire:loading.remove wire:target="sendAnnouncement">
                Kirim Pengumuman Sekarang
            </span>
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