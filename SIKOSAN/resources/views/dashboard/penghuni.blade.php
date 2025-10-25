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
    </style>

</head>

<body class="min-h-screen">
    <header class="fixed top-0 left-0 w-full bg-white shadow-sm z-10 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <button id="profile-menu-button" class="flex items-center rounded-full focus:outline-none" aria-haspopup="true" aria-expanded="false">
                        <div class="w-10 h-10 flex items-center justify-center bg-purple-100 rounded-full">
                            <i class='bx bxs-user bx-sm text-purple-500'></i>
                        </div>
                    </button>

                    <div id="profile-menu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow py-1 ring-1 ring-black ring-opacity-5">
                        <!-- Ubah link Profil Saya untuk mengarah ke profilpenghuni.blade.php -->
                        <a href="{{ route('profil.penghuni') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                        <button onclick="openLogoutModal()" class="w-full text-left px-4 py-2 text-sm text-red-600 font-semibold hover:bg-gray-100">
                            Logout
                        </button>
                    </div>
                </div>

                <span class="text-2xl font-bold text-teal-700">Sikosan</span>
            </div>

            <nav class="flex items-center space-x-6 md:space-x-8 text-sm md:text-base">
                <!-- Contact Dropdown -->
                <div class="relative">
                    <button id="contact-dropdown-button" class="text-teal-700 font-semibold focus:outline-none flex items-center" type="button">
                        Contact
                    </button>

                    <div id="contact-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl py-2 ring-1 ring-black ring-opacity-5 z-20 transition-all duration-300 transform origin-top-right">
                        <a href="https://wa.me/+6281905137498" target="_blank" class="flex items-center px-3 py-2 text-sm text-gray-800 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                            <i class='bx bxl-whatsapp text-lg mr-2 text-green-500'></i>
                            <div class="flex flex-col">
                                 <span class="font-semibold">WhatsApp</span>
                                 <span class="text-xs text-gray-500">+62 819-0513-7498</span>
                            </div>
                        </a>
                        <a href="https://instagram.com/sikosanapp" target="_blank" class="flex items-center px-3 py-2 text-sm text-gray-800 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                            <i class='bx bxl-instagram text-lg mr-2 text-pink-500'></i>
                            <div class="flex flex-col">
                                 <span class="font-semibold">Instagram</span>
                                 <span class="text-xs text-gray-500">@sikosanapp</span>
                            </div>
                        </a>
                        <a href="mailto:support@sikosan.com" class="flex items-center px-3 py-2 text-sm text-gray-800 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                            <i class='bx bx-envelope text-lg mr-2 text-blue-500'></i>
                            <div class="flex flex-col">
                                 <span class="font-semibold">Email</span>
                                 <span class="text-xs text-gray-500">support@sikosan.com</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- About Us Button -->
                <div class="relative">
                    <button onclick="openAboutModal()" class="text-teal-700 font-semibold focus:outline-none flex items-center" type="button">
                        About Us
                    </button>
                </div>
            </nav>
        </div>
    </header>

    {{-- jika tidak ada kos --}}
    @if($kosCollection->isEmpty())
    <main class="flex items-center justify-center h-full px-60">
        <div class=" w-full max-w-5xl mx-auto p-8">
            <div class="p-12 text-center flex flex-col items-center">

                <h1 class="text-4xl font-bold text-teal-700">Selamat Datang di Sikosan</h1>
                <p class="text-lg text-teal-700 mt-2">Kamu belum terdaftar di kos manapun</p>

                <div class="my-12">
                    <p class="text-2xl text-teal-700 pt-20">Silahkan masukkan kode unik kos</p>
                    <form action="" method="POST" class="mt-8">
                        @csrf
                        <input
                            type="text"
                            name="kode_unik"
                            id="kode_unik"
                            class="mt-4 block w-full max-w-md mx-auto px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-center text-lg"
                            placeholder="Masukkan kode unik">
                        @error('kode_unik')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                        <div class="mt-8 flex justify-center">
                            <button type="submit" class="w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center text-teal-500 hover:bg-teal-50 hover:shadow-xl transition-all duration-300">
                                <i class='bx bx-plus' style='font-size: 5rem;'></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>
    @endif

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

        document.addEventListener('keydown', function(e) {
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