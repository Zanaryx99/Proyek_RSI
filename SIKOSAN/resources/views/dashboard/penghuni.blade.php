<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Penghuni</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
        }

        .rounded-card {
            border: 2px solid #dbe2ea;
            border-radius: 1.5rem;
        }

        .add-button {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #e6eef5;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .2s;
        }

        .add-button:hover {
            background: #dbe2ea;
        }

        .kos-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .action-button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #0d9488;
            color: white;
        }

        .btn-primary:hover {
            background: #0f766e;
        }

        .btn-secondary {
            background: white;
            color: #0d9488;
            border: 2px solid #0d9488;
        }

        .btn-secondary:hover {
            background: #f0fdfa;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }
    </style>

</head>

<body class="min-h-screen">
    <x-header />

    {{-- jika tidak ada kamar yang ditempati --}}
    @if(!$kamarDitempati)
        <main class="flex items-center justify-center h-full px-60">
            <div class=" w-full max-w-5xl mx-auto p-8">
                <div class="p-12 text-center flex flex-col items-center">

                    <h1 class="text-4xl font-bold text-teal-700">Selamat Datang di Sikosan</h1>
                    <p class="text-lg text-teal-700 mt-2">Kamu belum terdaftar di kos manapun</p>

                    <div class="my-12">
                        <p class="text-2xl text-teal-700 pt-20">Silahkan masukkan kode unik kamar</p>
                        <form action="{{ route('kamar.daftar') }}" method="POST" class="mt-8">
                            @csrf
                            <input type="text" name="kode_unik" id="kode_unik"
                                class="mt-4 block w-full max-w-md mx-auto px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-center text-lg"
                                placeholder="Masukkan kode unik" value="{{ old('kode_unik') }}" required>

                            @if($errors->has('kode_unik'))
                                <p class="text-sm text-red-600 mt-2 text-center">{{ $errors->first('kode_unik') }}</p>
                            @endif

                            <div class="mt-8 flex justify-center">
                                <button type="submit"
                                    class="w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center text-teal-500 hover:bg-teal-50 hover:shadow-xl transition-all duration-300">
                                    <i class='bx bx-plus' style='font-size: 5rem;'></i>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>

    @else
        {{-- jika sudah ada kamar yang ditempati --}}
        <main class="pt-24 pb-12">
            <div class="max-w-4xl mx-auto px-4">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-teal-800">Kos Saya</h1>
                    <p class="text-gray-600 mt-2">Hai, {{ Auth::user()->nama_lengkap ?? Auth::user()->name }}!</p>
                    <p class="text-gray-600">Ini kos yang lagi kamu huni</p>
                </div>

                <!-- Menampilkan pesan sukses -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Kartu Kos -->
                @foreach($kosCollection as $kos)
                    <div class="kos-card mb-8">
                        <!-- Foto Kos -->
                        <div class="relative">
                            <img src="{{ $kos->foto ? asset('storage/' . $kos->foto) : asset('images/placeholder.png') }}"
                                alt="Foto {{ $kos->nama_kos }}" class="w-full h-64 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        </div>

                        <!-- Info Kos -->
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $kos->nama_kos }}</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class='bx bx-male-female text-blue-600'></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Jenis Kos</p>
                                        <p class="font-semibold text-gray-800">
                                            @if($kos->jenis == 'Pria')
                                                Laki-Laki
                                            @elseif($kos->jenis == 'Wanita')
                                                Perempuan
                                            @else
                                                Campur
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class='bx bx-map text-green-600'></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Lokasi</p>
                                        <p class="font-semibold text-gray-800">{{ $kos->lokasi }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Kamar yang Ditempati -->
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold text-teal-800">Kamar Anda</h3>
                                        <p class="text-teal-700">{{ $kamarDitempati->nama_kamar }}</p>
                                        <p class="text-sm text-teal-600">Rp
                                            {{ number_format($kamarDitempati->harga_sewa, 0, ',', '.') }}/bulan</p>
                                    </div>
                                    <span class="bg-teal-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        Terisi
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <button class="action-button btn-primary">
                                    <i class='bx bx-chat'></i>
                                    Chat Pemilik
                                </button>

                                <button class="action-button btn-secondary">
                                    <i class='bx bx-star'></i>
                                    Ulas Kos
                                </button>

                                <form action="{{ route('kamar.keluar', $kamarDitempati->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri kontrak?')">
                                    @csrf
                                    <button type="submit" class="action-button btn-danger w-full">
                                        <i class='bx bx-log-out'></i>
                                        Akhiri Kontrak
                                    </button>
                                </form>
                            </div>

                            <!-- Fasilitas Umum -->
                            @if($kos->fasilitas_umum)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h3 class="font-semibold text-gray-800 mb-3">Fasilitas Umum</h3>
                                    <p class="text-gray-600">{{ $kos->fasilitas_umum }}</p>
                                </div>
                            @endif

                            <!-- Peraturan Umum -->
                            @if($kos->peraturan_umum)
                                <div class="mt-4">
                                    <h3 class="font-semibold text-gray-800 mb-3">Peraturan Kos</h3>
                                    <p class="text-gray-600">{{ $kos->peraturan_umum }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    @endif

    <!-- Modal About Us -->
    <div id="aboutModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeAboutModal()"></div>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0">
                            <i class='bx bx-info-circle text-2xl text-blue-600'></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">
                                Tentang Sikosan
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">
                                    Sikosan adalah platform pencarian kos terdepan di Indonesia yang membantu Anda
                                    menemukan tempat tinggal yang tepat dengan mudah dan cepat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeAboutModal()"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-teal-600 text-base font-semibold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:w-auto sm:text-sm">
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

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0">
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
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors sm:w-auto sm:text-sm">
                            <i class='bx bx-log-out mr-2'></i>
                            Ya, Logout
                        </button>
                    </form>
                    <button type="button" onclick="closeLogoutModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // FUNGSI UNTUK MENGELOLA DROPDOWN
        function setupDropdownToggle(buttonId, menuId) {
            const btn = document.getElementById(buttonId);
            const menu = document.getElementById(menuId);

            if (!btn || !menu) return;

            // Toggle visibility on click
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');

                // Menutup dropdown lain saat yang ini dibuka
                const dropdowns = ['profile-menu', 'contact-dropdown'];
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

        document.addEventListener('DOMContentLoaded', function () {
            // Setup semua dropdown
            setupDropdownToggle('profile-menu-button', 'profile-menu');
            setupDropdownToggle('contact-dropdown-button', 'contact-dropdown');
        });

        // Fungsi untuk membuka modal About Us
        function openAboutModal() {
            const modal = document.getElementById('aboutModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Tutup semua dropdown saat modal dibuka
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('contact-dropdown').classList.add('hidden');
        }

        // Fungsi untuk menutup modal About Us
        function closeAboutModal() {
            const modal = document.getElementById('aboutModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Fungsi untuk membuka modal Logout
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Tutup semua dropdown saat modal dibuka
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('contact-dropdown').classList.add('hidden');
        }

        // Fungsi untuk menutup modal Logout
        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAboutModal();
                closeLogoutModal();
                // Tutup semua dropdown saat Esc
                document.getElementById('profile-menu').classList.add('hidden');
                document.getElementById('contact-dropdown').classList.add('hidden');
            }
        });
    </script>
</body>

</html>