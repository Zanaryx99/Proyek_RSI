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

    {{-- jika tidak ada kos --}}
    @if($kosCollection->isEmpty())
    <main class="flex items-center justify-center h-full px-60">
        <div class=" w-full max-w-5xl mx-auto p-8">
            <div class="p-12 text-center flex flex-col items-center">
                <h1 class="text-4xl font-bold text-teal-700">Selamat Datang di Sikosan</h1>
                <p class="text-xl text-teal-700 mt-2">Kamu belum terdaftar di kos manapun</p>

                <form action="" method="POST" class="mt-20">
                    @csrf
                    <label for="kode_unik" class="text-2xl text-teal-700 block">Silahkan masukkan kode unik kos</label>
                    <input
                        type="text"
                        name="kode_unik"
                        id="kode_unik"
                        class="mt-4 block w-full max-w-md mx-auto px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-center text-lg">
                    @error('kode_unik')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror

                    <div class="mt-12 flex justify-center">
                        <button type="submit" class="w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center text-teal-500 hover:bg-teal-50 hover:shadow-xl transition-all duration-300">
                            <i class='bx bx-plus' style='font-size: 5rem;'></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

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
</body>

</html>