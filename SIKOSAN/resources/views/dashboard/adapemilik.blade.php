<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard isi Pemilik</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
        }

        .custom-teal {
            color: #00838F;
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
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                        <a href="/logout" class="block px-4 py-2 text-sm text-red-600 font-semibold hover:bg-gray-100">Logout</a>
                    </div>
                </div>

                <span class="text-2xl font-bold text-teal-700">Sikosan</span>
            </div>

            <nav class="flex items-center space-x-6 md:space-x-8 text-sm md:text-base">
                <a href="#" class="text-teal-700 font-semibold ">contact</a>
                <a href="#" class="text-teal-700 font-semibold ">about us</a>
            </nav>
        </div>
    </header>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

            <!-- Header Halaman -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-teal-800 font-playfair">Daftar Kos Anda</h1>
                <p class="text-gray-600 mt-2">Kelola semua properti kos Anda dari sini.</p>
            </div>

            <!-- Grid Kartu Kos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

                <!-- Jabarin kos -->
                @foreach ($kosCollection as $kos)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-transform duration-300">
                    <!-- Gambar Kos -->
                    <div class="relative">
                        <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto {{ $kos->nama_kos }}" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-40 transition-all duration-300"></div>
                    </div>
                    <!-- Kartu Kos -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 truncate">{{ $kos->nama_kos }}</h3>
                        <p class="text-gray-500 text-sm mt-2">
                            Masih Tersedia {{-- Logika untuk kamar kosong bisa ditambahkan di sini --}}
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('kos.show', $kos->id) }}" class="w-full text-center inline-block px-4 py-2 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Menambah Kos Baru -->
                <a href="{{ route('kos.create') }}" class="flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-2xl shadow-lg hover:border-teal-500 hover:bg-gray-100 transition-all duration-300 min-h-[280px]">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center w-20 h-20 bg-gray-200 rounded-full group-hover:bg-teal-100">
                            <i class='bx bx-plus text-4xl text-gray-500 group-hover:text-teal-600'></i>
                        </div>
                        <p class="mt-4 text-lg font-semibold text-gray-600">Tambah Kos</p>
                    </div>
                </a>

            </div>

            @if($kosCollection->isEmpty())
            <div class="text-center py-16 mt-8 bg-white rounded-lg shadow-md">
                <p class="text-gray-500 text-lg">Anda belum memiliki data kos.</p>
                <p class="mt-2 text-gray-500">Silakan klik kartu "Tambah Kos" untuk memulai.</p>
            </div>
            @endif

        </div>
    </div>
    <script>
        (function() {
            const btn = document.getElementById('profile-menu-button');
            const menu = document.getElementById('profile-menu');

            if (!btn || !menu) return;

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        })();
    </script>
    </hmtl>