<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Kontrol Kos</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
        }
    </style>
</head>

<body class="min-h-screen">
    <x-header />

    <main class="max-w-screen-xl mx-auto mt-20 p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <a href="{{ route('pemilik.dashboard') }}"
                class="flex items-center text-teal-600 hover:text-teal-700 transition-colors">
                <i class='bx bx-arrow-back text-2xl mr-2'></i>
                <span class="font-medium">Kembali</span>
            </a>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <div class="relative">
                    <img src="{{ $kos->foto ? asset('storage/' . $kos->foto) : 'https://images.unsplash.com/photo-1585098944543-9b57827238fb?q=80&w=2070' }}"
                        alt="Foto Kost" class="w-full h-60 object-cover rounded-lg">
                    <button
                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-2 hover:bg-white"><i
                            class='bx bx-chevron-left text-2xl'></i></button>
                    <button
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-2 hover:bg-white"><i
                            class='bx bx-chevron-right text-2xl'></i></button>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">{{ $kos->nama_kos ?? 'Nama Kost' }}</h2>
                <p class="text-sm text-gray-500">Jenis Kos: {{ $kos->jenis ?? '-' }}</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                <div>
                    <h3 class="font-bold text-gray-800">Lokasi</h3>
                    <p class="text-gray-600 text-sm">{{ $kos->lokasi ?? 'Lokasi belum diisi.' }}</p>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Fasilitas Umum</h3>
                    <ul class="text-gray-600 text-sm space-y-1">
                        @if(!empty($kos->fasilitas_umum))
                        @foreach(preg_split("/\r\n|\n|\r/", $kos->fasilitas_umum) as $f)
                        @if(trim($f) !== '')
                        <li class="flex items-center"><i class='bx bx-check-circle custom-icon mr-2'></i>{{ $f }}</li>
                        @endif
                        @endforeach
                        @else
                        <li class="text-gray-500">Belum ada fasilitas umum yang didaftarkan.</li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Peraturan Umum Kos</h3>
                    <ul class="text-gray-600 text-sm space-y-1 list-disc list-inside">
                        @if(!empty($kos->peraturan_umum))
                        @foreach(preg_split("/\r\n|\n|\r/", $kos->peraturan_umum) as $peraturan)
                        @if(trim($peraturan) !== '')
                        <li>{{ $peraturan }}</li>
                        @endif
                        @endforeach
                        @else
                        <li class="list-none text-gray-500">Belum ada peraturan umum yang didaftarkan.</li>
                        @endif
                    </ul>
                </div>

                <a href="{{ route('kamar.index', $kos->id) }}"
                    class="block w-full mt-4 py-2 px-4 bg-teal-50 text-teal-700 font-semibold rounded-lg hover:bg-teal-100 transition-colors text-center">
                    <i class='bx bx-plus-circle mr-1'></i> Kelola Kamar
                </a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="font-bold text-gray-800">Rating</h3>
                <div class="flex items-center mt-1">
                    @php
                    $displayRating = $rating ? round($rating * 2) / 2 : null; // pembulatan 0.5
                    $fullStars = $displayRating ? floor($displayRating) : 0;
                    $halfStar = $displayRating && ($displayRating - $fullStars) >= 0.5;
                    @endphp

                    @for($i=0; $i < $fullStars; $i++)
                        <i class='bx bxs-star text-yellow-500'></i>
                        @endfor
                        @if($halfStar)
                        <i class='bx bxs-star-half text-yellow-500'></i>
                        @endif
                        @for($i = $fullStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                            <i class='bx bxs-star text-gray-300'></i>
                            @endfor

                            <span class="ml-2 text-sm font-semibold text-gray-700">
                                {{ $displayRating ? $displayRating . '/5' : 'Belum ada penilaian' }}
                            </span>
                </div>
            </div>
            <button
                class="w-full mt-4 py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                Buat Pengumuman
            </button>
        </div>

        </div>

        <div class="lg:col-span-2 mt-20 space-y-6">

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Rincian Pemasukan Bulan Ini</h2>
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">Nama Kamar</th>
                            <th scope="col" class="px-4 py-3">Tipe Kamar</th>
                            <th scope="col" class="px-4 py-3">Harga Sewa</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Lakukan perulangan untuk SETIAP kamar yang dihuni --}}
                        @forelse ($kamarDihuni as $kamar)
                        <tr class="border-b">
                            <td class="px-4 py-3 font-medium">{{ $kamar->nama_kamar }}</td>
                            <td class="px-4 py-3">{{ $kamar->tipe_kamar ?: 'Tidak ada tipe' }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($kamar->harga_sewa, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        {{-- Tampil jika tidak ada kamar yang dihuni --}}
                        <tr class="border-b">
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada pemasukan bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="font-semibold text-gray-800">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-right">Total Pemasukan</td>
                            {{-- Gunakan variabel total pemasukan yang sudah dihitung --}}
                            <td class="px-4 py-3">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Tagihan Bulan Lalu</h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Penghuni Kos</h2>

                <div class="space-y-3">
                    {{-- Gunakan @forelse untuk looping data kamarDihuni --}}
                    @forelse ($kamarDihuni as $kamar)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div class="flex items-center">
                            {{-- Tampilkan foto profil default atau dari user jika ada --}}
                            <img class="w-10 h-10 rounded-full mr-3"
                                src="{{asset('storage/' . $kamar->user->foto_profile) ?? 'https://i.pravatar.cc/40?u=' . $kamar->user_id }}"
                                alt="Avatar">
                            <div>
                                {{-- Tampilkan nama penghuni melalui relasi user --}}
                                <p class="font-medium text-gray-900">{{ $kamar->user->nama_lengkap ?? 'Data Penghuni Error' }}</p>
                                {{-- Tampilkan nama/nomor kamar --}}
                                <p class="text-xs text-gray-500">Menempati: {{ $kamar->nama_kamar ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            {{-- Tombol aksi bisa ditambahkan di sini --}}
                            <button class="p-2 rounded-full hover:bg-gray-200" title="Kirim Pesan">
                                <i class='bx bx-message-square-dots text-xl text-gray-600'></i>
                            </button>
                            <button class="p-2 rounded-full hover:bg-gray-200" title="Lihat Detail">
                                <i class='bx bx-search-alt-2 text-xl text-gray-600'></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    {{-- Bagian ini akan tampil jika tidak ada penghuni sama sekali --}}
                    <div class="text-center py-4 text-gray-500">
                        <p>Belum ada penghuni saat ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </main>


    <!-- Modal About Us -->
    <div id="aboutModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeAboutModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0">
                            <i class='bx bx-info-circle text-2xl text-blue-600'></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">
                                Tentang Sikosan
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">
                                    Sikosan adalah platform pencarian kos terdepan di Indonesia yang membantu Anda menemukan tempat tinggal yang tepat dengan mudah dan cepat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeAboutModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-teal-600 text-base font-semibold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Logout -->
    <div id="logoutModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeLogoutModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0">
                            <i class='bx bx-log-out text-2xl text-red-600'></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">
                                Konfirmasi Logout
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">
                                    Apakah Anda yakin ingin keluar dari akun ini?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <form action="/logout" method="GET" class="w-full sm:w-auto">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors sm:w-auto sm:text-sm">
                            <i class='bx bx-log-out mr-2'></i>
                            Ya, Logout
                        </button>
                    </form>
                    <button type="button" onclick="closeLogoutModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // FUNGSI UMUM UNTUK MENGELOLA TOGGLE DROPDOWN
    function setupDropdownToggle(buttonId, menuId) {
        const btn = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);

        if (!btn || !menu) return;

        // Toggle visibility on click
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('hidden');
            // Menutup dropdown lain saat yang ini dibuka
            const dropdowns = ['contact-dropdown', 'profile-menu'];
            dropdowns.forEach(dropdown => {
                if (dropdown !== menuId) {
                    document.getElementById(dropdown).classList.add('hidden');
                }
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Setup semua dropdown
        setupDropdownToggle('profile-menu-button', 'profile-menu');
        setupDropdownToggle('contact-dropdown-button', 'contact-dropdown');
    });

    // Fungsi untuk membuka modal About Us
    function openAboutModal() {
        const modal = document.getElementById('aboutModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Pastikan semua dropdown tertutup
        document.getElementById('profile-menu').classList.add('hidden');
        document.getElementById('contact-dropdown').classList.add('hidden');
    }

    // Fungsi untuk menutup modal About Us
    function closeAboutModal() {
        const modal = document.getElementById('aboutModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Pastikan semua dropdown tertutup
        document.getElementById('profile-menu').classList.add('hidden');
        document.getElementById('contact-dropdown').classList.add('hidden');
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAboutModal();
            closeLogoutModal();
            closeDeleteModal();
            // Tutup semua dropdown saat Esc
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('contact-dropdown').classList.add('hidden');
        }
    });
</script>

</html>