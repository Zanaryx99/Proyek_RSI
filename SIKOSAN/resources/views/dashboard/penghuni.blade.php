<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penghuni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-md">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Dashboard Penghuni Kos</h1>
                    <div class="flex items-center space-x-4">
                        <span>Halo, {{ Auth::user()->username }}</span>
                        <a href="/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Kamar</h3>
                    <p class="text-gray-600">Lihat detail kamar dan fasilitas</p>
                </div>


                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Pembayaran</h3>
                    <p class="text-gray-600">Cek tagihan dan riwayat pembayaran</p>
                </div>


                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Pengaduan</h3>
                    <p class="text-gray-600">Ajukan pengaduan atau permintaan</p>
                </div>
            </div>
        </main>
    </div>
</body>

</html>