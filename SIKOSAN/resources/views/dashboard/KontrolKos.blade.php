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
    </header>

    <main class="max-w-screen-xl mx-auto mt-20 p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <a href="{{ route('pemilik.dashboard') }}" class="flex items-center text-teal-600 hover:text-teal-700 transition-colors">
                <i class='bx bx-arrow-back text-2xl mr-2'></i>
                <span class="font-medium">Kembali</span>
            </a>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1585098944543-9b57827238fb?q=80&w=2070" alt="Foto Kost Holly" class="w-full h-60 object-cover rounded-lg">
                    <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-2 hover:bg-white"><i class='bx bx-chevron-left text-2xl'></i></button>
                    <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-2 hover:bg-white"><i class='bx bx-chevron-right text-2xl'></i></button>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Kost Holly</h2>
                <p class="text-sm text-gray-500">Jenis Kos: Putri</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                <div>
                    <h3 class="font-bold text-gray-800">Lokasi</h3>
                    <p class="text-gray-600 text-sm">Jalan Soekarno Perumahan Soekarno No. 2, Lowokwaru, Kota Malang, Jawa Timur</p>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Fasilitas Umum</h3>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li class="flex items-center"><i class='bx bx-check-circle custom-icon mr-2'></i>Kamar mandi luar plus kloset luar</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Fasilitas Kamar (Tipe A)</h3>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li class="flex items-center"><i class='bx bx-check-circle custom-icon mr-2'></i>Kasur</li>
                        <li class="flex items-center"><i class='bx bx-check-circle custom-icon mr-2'></i>Meja dan Kursi</li>
                    </ul>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <p class="text-sm text-gray-600"><strong>Harga Sewa:</strong> Rp 9.600.000 / Tahun</p>
                    <p class="text-sm text-gray-600"><strong>Minimal Waktu Sewa:</strong> 6 Bulan</p>
                </div>

                <button class="w-full mt-4 py-2 px-4 bg-teal-50 text-teal-700 font-semibold rounded-lg hover:bg-teal-100 transition-colors">
                    <i class='bx bx-plus-circle mr-1'></i> Tambah Tipe Kamar
                </button>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="font-bold text-gray-800">Kode Unik</h3>
                <div class="text-sm text-gray-500 bg-gray-100 p-2 rounded-md mt-2">
                    <p>Tipe A: k05HollyA_GHYM...</p>
                    <p>Tipe B: k05HollyB_GHYM...</p>
                </div>
                <div class="mt-4 border-t pt-4">
                    <h3 class="font-bold text-gray-800">Rating</h3>
                    <div class="flex items-center mt-1">
                        <i class='bx bxs-star text-yellow-500'></i>
                        <i class='bx bxs-star text-yellow-500'></i>
                        <i class='bx bxs-star text-yellow-500'></i>
                        <i class='bx bxs-star text-yellow-500'></i>
                        <i class='bx bxs-star-half text-yellow-500'></i>
                        <span class="ml-2 text-sm font-semibold text-gray-700">4.5/5</span>
                    </div>
                </div>
                <button class="w-full mt-4 py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Buat Pengumuman
                </button>
            </div>

        </div>

        <div class="lg:col-span-2 mt-20 space-y-6">

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Penghasilan Bulan Lalu</h2>
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">Jenis Kamar</th>
                            <th scope="col" class="px-4 py-3">Total Penghuni</th>
                            <th scope="col" class="px-4 py-3">Harga Sewa</th>
                            <th scope="col" class="px-4 py-3">Total Penghasilan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-4 py-3">Tipe A</td>
                            <td class="px-4 py-3">5</td>
                            <td class="px-4 py-3">Rp 800.000</td>
                            <td class="px-4 py-3">Rp 4.000.000</td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-4 py-3">Tipe B</td>
                            <td class="px-4 py-3">10</td>
                            <td class="px-4 py-3">Rp 800.000</td>
                            <td class="px-4 py-3">Rp 8.000.000</td>
                        </tr>
                    </tbody>
                    <tfoot class="font-semibold text-gray-800">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right">Total Pendapatan</td>
                            <td class="px-4 py-3">Rp 12.000.000</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Tagihan Bulan Lalu</h2>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Penghuni Kos</h2>
                <div>
                    <h3 class="font-semibold text-gray-700 mt-4 mb-2">Tipe A</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center">
                                <img class="w-10 h-10 rounded-full mr-3" src="https://i.pravatar.cc/40?u=a" alt="Avatar">
                                <div>
                                    <p class="font-medium text-gray-900">Asep Slamet</p>
                                    <p class="text-xs text-green-600 font-semibold">Sudah Bayar</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 rounded-full hover:bg-gray-200"><i class='bx bx-message-square-dots text-xl text-gray-600'></i></button>
                                <button class="p-2 rounded-full hover:bg-gray-200"><i class='bx bx-search-alt-2 text-xl text-gray-600'></i></button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center">
                                <img class="w-10 h-10 rounded-full mr-3" src="https://i.pravatar.cc/40?u=b" alt="Avatar">
                                <div>
                                    <p class="font-medium text-gray-900">Dadang Koren</p>
                                    <p class="text-xs text-red-600 font-semibold">Belum Bayar</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 rounded-full hover:bg-gray-200"><i class='bx bx-message-square-dots text-xl text-gray-600'></i></button>
                                <button class="p-2 rounded-full hover:bg-gray-200"><i class='bx bx-search-alt-2 text-xl text-gray-600'></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-700 mt-4 mb-2">Tipe B</h3>
                </div>
            </div>
        </div>

    </main>

</body>
<script>
    // Profile Menu Toggle
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

</html>