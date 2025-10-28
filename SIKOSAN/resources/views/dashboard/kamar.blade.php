<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Pemilik - Sikosan</title>

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

        .img-preview {
            max-height: 160px;
            max-width: 100%;
            object-fit: cover;
            border-radius: .5rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-tersedia {
            background-color: #10b981;
            color: white;
        }
        
        .status-terisi {
            background-color: #3b82f6;
            color: white;
        }
        
        .status-renovasi {
            background-color: #f59e0b;
            color: white;
        }
    </style>
</head>

<body>
    <x-header />

    <!-- Konten utama -->
    <main class="pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4">
            {{-- CEK APAKAH INI MENAMPILKAN KOS ATAU KAMAR --}}
            @if(isset($kosCollection) && !$kosCollection->isEmpty())

            {{-- TAMPILAN UNTUK KOS (TIDAK BERUBAH) --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-teal-800">Daftar Kos Anda</h1>
                <p class="text-gray-600 mt-2">Kelola semua properti kos Anda dari sini.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach ($kosCollection as $kos)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="relative">
                        <img
                            src="{{ $kos->foto ? asset('storage/' . $kos->foto) : asset('images/placeholder.png') }}"
                            alt="Foto {{ $kos->nama_kos }}"
                            class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-40 transition-all duration-300"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 truncate">{{ $kos->nama_kos }}</h3>
                        <p class="text-gray-500 text-sm mt-2">
                            {{ $kos->jenis }} - {{ $kos->lokasi }}
                        </p>
                        <div class="mt-6 space-y-2">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('kos.edit', $kos->id) }}" class="w-full text-center px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                    <i class='bx bx-edit-alt'></i> Edit
                                </a>
                                <form action="{{ route('kos.destroy', $kos->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kos ini?')"
                                        type="submit"
                                        class="w-full px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                        <i class='bx bx-trash'></i> Hapus
                                    </button>
                                </form>
                            </div>
                            <div>
                                <a href="{{ route('kos.kontrol', $kos->id) }}" class="w-full text-center inline-block px-4 py-2 bg-teal-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300">
                                    Kelola Kamar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <a href="{{ route('kos.create') }}" class="flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-2xl shadow-lg hover:border-teal-500 hover:bg-gray-100 transition-all duration-300 min-h-[400px] group">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center w-20 h-20 bg-gray-200 rounded-full group-hover:bg-teal-100 transition-colors">
                            <i class='bx bx-plus text-4xl text-gray-500 group-hover:text-teal-600'></i>
                        </div>
                        <p class="mt-4 text-lg font-semibold text-gray-600">Tambah Kos</p>
                    </div>
                </a>

            </div>

            @elseif(isset($kamar))

            {{-- TAMPILAN UNTUK KAMAR --}}
            <div class="mb-8">
                <!-- Tombol Back ke Kontrol Kos - Dipindah ke atas sebelah kiri -->
                <div class="mb-6">
                    @if(isset($kos) && $kos->id)
                    <a href="{{ route('kos.kontrol', $kos->id) }}"
                        class="inline-flex items-center text-teal-600 hover:text-teal-700 transition-colors mb-4">
                        <i class='bx bx-arrow-back text-xl mr-2'></i>
                        <span class="font-medium">Kembali ke Kontrol Kos</span>
                    </a>
                    @endif
                </div>

                <div>
                    <h1 class="text-4xl font-bold text-teal-800">Daftar Kamar Kos Anda</h1>
                    <p class="text-gray-600 mt-2">Kelola semua kamar kos Anda dari sini.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                {{-- Hanya tampilkan kamar jika koleksi tidak kosong --}}
                @if(isset($kamar) && !$kamar->isEmpty())
                @foreach ($kamar as $kamarItem)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="relative">
                        <img
                            src="{{ $kamarItem->foto_kamar ? asset('storage/' . $kamarItem->foto_kamar) : asset('images/placeholder-kamar.png') }}"
                            alt="Foto {{ $kamarItem->nama_kamar }}"
                            class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-40 transition-all duration-300"></div>
                        <div class="absolute top-3 right-3 bg-teal-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $kamarItem->tipe_kamar ?? 'Standard' }}
                        </div>
                        <!-- Badge Status Ketersediaan -->
                        <div class="absolute top-3 left-3">
                            @if($kamarItem->status === 'tersedia')
                                <span class="status-badge status-tersedia">Tersedia</span>
                            @elseif($kamarItem->status === 'terisi')
                                <span class="status-badge status-terisi">Terisi</span>
                            @elseif($kamarItem->status === 'renovasi')
                                <span class="status-badge status-renovasi">Renovasi</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 truncate">{{ $kamarItem->nama_kamar }}</h3>
                        <p class="text-teal-600 font-semibold text-lg mt-2">Rp {{ number_format($kamarItem->harga_sewa, 0, ',', '.') }}/bulan</p>
                        <p class="text-gray-500 text-sm mt-1">Minimal sewa: {{ $kamarItem->minimal_waktu_sewa }} bulan</p>

                        <!-- Kode Unik untuk Penghuni -->
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700">Kode Unik Kamar:</p>
                            <div class="flex items-center justify-between mt-1">
                                <code class="text-lg font-bold text-teal-700 bg-white px-2 py-1 rounded border">
                                    {{ $kamarItem->kode_unik }}
                                </code>
                                <button onclick="copyKodeUnik('{{ $kamarItem->kode_unik }}')" 
                                        class="text-teal-600 hover:text-teal-700 transition-colors"
                                        title="Salin kode">
                                    <i class='bx bx-copy text-xl'></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($kamarItem->status === 'tersedia')
                                    Berikan kode ini kepada penghuni untuk bergabung
                                @elseif($kamarItem->status === 'terisi')
                                    Kamar sedang ditempati oleh penghuni
                                @else
                                    Kamar sedang tidak tersedia
                                @endif
                            </p>
                        </div>

                        @if($kamarItem->kos)
                        <p class="text-gray-600 text-sm mt-1">Kos: {{ $kamarItem->kos->nama_kos }}</p>
                        @endif

                        @if($kamarItem->deskripsi)
                        <p class="text-gray-600 text-sm mt-3 line-clamp-2">Deskripsi: {{ Str::limit($kamarItem->deskripsi, 80) }}</p>
                        @endif

                        <!-- Form Update Status - HANYA untuk status tersedia dan renovasi -->
                        @if($kamarItem->status !== 'terisi')
                        <div class="mt-4">
                            <label for="status_{{ $kamarItem->id }}" class="block text-sm font-medium text-gray-700 mb-1">Ubah Status:</label>
                            <form action="{{ route('kamar.update-status', $kamarItem->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" id="status_{{ $kamarItem->id }}" 
                                        class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="tersedia" {{ $kamarItem->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="renovasi" {{ $kamarItem->status === 'renovasi' ? 'selected' : '' }}>Renovasi</option>
                                </select>
                                <button type="submit" 
                                        class="bg-teal-600 text-white px-3 py-2 rounded-md text-sm hover:bg-teal-700 transition-colors">
                                    <i class='bx bx-check'></i>
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-1">
                                Status "Terisi" akan otomatis aktif ketika penghuni menggunakan kode unik
                            </p>
                        </div>
                        @else
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">
                                <i class='bx bx-info-circle'></i> 
                                Status "Terisi" aktif karena kamar sedang ditempati penghuni. 
                                Status akan otomatis kembali ke "Tersedia" ketika penghuni keluar.
                            </p>
                        </div>
                        @endif

                        <div class="mt-6 space-y-2">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('kamar.edit', $kamarItem->id) }}" class="w-full text-center px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                    <i class='bx bx-edit-alt'></i> Edit
                                </a>
                                <form action="{{ route('kamar.destroy', $kamarItem->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')"
                                        type="submit"
                                        class="w-full px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                        <i class='bx bx-trash'></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                {{-- CARD TAMBAH KAMAR BARU (Sekali klik akan membuka modal) --}}
                <button onclick="openTambahKamarModal()" class="flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-2xl shadow-lg hover:border-teal-500 hover:bg-gray-100 transition-all duration-300 min-h-[400px] group w-full">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center w-20 h-20 bg-gray-200 rounded-full group-hover:bg-teal-100 transition-colors">
                            <i class='bx bx-plus text-4xl text-gray-500 group-hover:text-teal-600'></i>
                        </div>
                        <p class="mt-4 text-lg font-semibold text-gray-600">Tambah Kamar</p>
                    </div>
                </button>

            </div>
            @endif


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

    <!-- Modal Tambah Kamar -->
    <div id="tambahKamarModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeTambahKamarModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-teal-700">Tambah Kamar Baru</h3>
                        <button onclick="closeTambahKamarModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <!-- Form Tambah Kamar -->
                    <form action="{{ route('kamar.store', $kos->id ?? 0) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Foto Kamar -->
                        <div>
                            <label for="foto_kamar" class="block text-sm font-medium text-gray-600 mb-1">
                                Foto Kamar *
                            </label>
                            <div id="dropzone"
                                class="mt-1 flex flex-col items-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-teal-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    <div class="flex text-sm text-gray-600 items-center justify-center gap-2">
                                        <button type="button" id="btn-browse"
                                            class="bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 px-3 py-1 border border-teal-300">
                                            Upload Foto
                                        </button>
                                        <p class="pl-1 text-sm">atau taruh ke sini</p>
                                    </div>

                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>

                                <input id="foto_kamar" name="foto_kamar" type="file" accept="image/*" class="hidden" required>

                                <div id="preview-area" class="mt-4 w-full hidden">
                                    <img id="img-preview" class="img-preview mx-auto border border-gray-300" src="#"
                                        alt="Preview foto" />
                                    <p id="file-name" class="text-center text-sm text-gray-600 mt-2"></p>
                                    <button type="button" id="btn-remove-preview"
                                        class="mx-auto mt-2 block text-red-600 hover:text-red-700 text-sm font-medium">
                                        <i class='bx bx-x-circle'></i> Hapus Preview
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Kamar -->
                        <div>
                            <label for="nama_kamar" class="block text-sm font-medium text-gray-600 mb-1">Nama Kamar *</label>
                            <input type="text" name="nama_kamar" id="nama_kamar"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                value="{{ old('nama_kamar') }}"
                                placeholder="Contoh: Kamar A1, Kamar Deluxe 101" required>
                        </div>

                        <!-- Tipe Kamar -->
                        <div>
                            <label for="tipe_kamar" class="block text-sm font-medium text-gray-600 mb-1">Tipe Kamar</label>
                            <select name="tipe_kamar" id="tipe_kamar"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2">
                                <option value="">-- Pilih Tipe Kamar --</option>
                                <option value="Standard" {{ old('tipe_kamar') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="Deluxe" {{ old('tipe_kamar') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="Premium" {{ old('tipe_kamar') == 'Premium' ? 'selected' : '' }}>Premium</option>
                                <option value="Eksekutif" {{ old('tipe_kamar') == 'Eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                            </select>
                        </div>

                        <!-- Harga Sewa -->
                        <div>
                            <label for="harga_sewa" class="block text-sm font-medium text-gray-600 mb-1">Harga Sewa per Bulan *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="text" name="harga_sewa" id="harga_sewa"
                                    class="w-full pl-10 border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                    value="{{ old('harga_sewa') }}"
                                    placeholder="0" oninput="formatCurrency(this)" required>
                            </div>
                        </div>

                        <!-- Minimal Waktu Sewa -->
                        <div>
                            <label for="minimal_waktu_sewa" class="block text-sm font-medium text-gray-600 mb-1">Minimal Waktu Sewa (bulan) *</label>
                            <input type="number" name="minimal_waktu_sewa" id="minimal_waktu_sewa" min="1" max="24"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                value="{{ old('minimal_waktu_sewa', 1) }}" required>
                        </div>

                        <!-- Status Kamar - HANYA tersedia dan renovasi -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-600 mb-1">Status Kamar *</label>
                            <select name="status" id="status" required
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2">
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="renovasi" {{ old('status') == 'renovasi' ? 'selected' : '' }}>Renovasi</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Status "Terisi" akan otomatis aktif ketika penghuni menggunakan kode unik
                            </p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Kamar</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                placeholder="Deskripsikan fasilitas dan keunggulan kamar ini...">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-4 flex gap-3">
                            <button type="button" onclick="closeTambahKamarModal()"
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                Batal
                            </button>

                            <button type="submit"
                                class="flex-1 bg-teal-600 text-white font-bold py-3 px-4 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300">
                                <i class='bx bx-check-circle'></i>
                                Daftarkan Kamar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        // Format currency function
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            if (value) {
                value = parseInt(value, 10).toLocaleString('id-ID');
                input.value = value;
            }
        }

        // Fungsi untuk menyalin kode unik
        function copyKodeUnik(kode) {
            navigator.clipboard.writeText(kode).then(() => {
                // Tampilkan notifikasi sukses
                showNotification('Kode unik berhasil disalin!', 'success');
            }).catch(err => {
                console.error('Gagal menyalin kode: ', err);
                showNotification('Gagal menyalin kode', 'error');
            });
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error'} text-xl'></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animasi masuk
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 100);
            
            // Animasi keluar setelah 3 detik
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Modal functions
        function openTambahKamarModal() {
            const modal = document.getElementById('tambahKamarModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeTambahKamarModal() {
            const modal = document.getElementById('tambahKamarModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openAboutModal() {
            const modal = document.getElementById('aboutModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Pastikan semua dropdown tertutup
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('contact-dropdown').classList.add('hidden');
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            // Setup semua dropdown
            setupDropdownToggle('profile-menu-button', 'profile-menu');
            setupDropdownToggle('contact-dropdown-button', 'contact-dropdown');

            // File upload functionality for tambah kamar modal
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('foto_kamar');
            const btnBrowse = document.getElementById('btn-browse');
            const btnRemovePreview = document.getElementById('btn-remove-preview');
            const previewArea = document.getElementById('preview-area');
            const imgPreview = document.getElementById('img-preview');
            const fileNameEl = document.getElementById('file-name');
            const MAX_BYTES = 2 * 1024 * 1024;

            function showPreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    previewArea.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
                fileNameEl.textContent = file.name;
            }

            function handleFile(file) {
                if (!file) return;
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar (PNG/JPG/GIF).');
                    fileInput.value = '';
                    previewArea.classList.add('hidden');
                    return;
                }
                if (file.size > MAX_BYTES) {
                    alert('Ukuran file maksimal 2MB.');
                    fileInput.value = '';
                    previewArea.classList.add('hidden');
                    return;
                }
                showPreview(file);
            }

            btnBrowse.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });

            dropzone.addEventListener('click', (e) => {
                if (e.target !== btnBrowse && !btnBrowse.contains(e.target) && e.target !== btnRemovePreview && !btnRemovePreview.contains(e.target)) {
                    fileInput.click();
                }
            });

            fileInput.addEventListener('change', (e) => {
                const f = e.target.files && e.target.files[0];
                handleFile(f);
            });

            // Remove preview button
            if (btnRemovePreview) {
                btnRemovePreview.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fileInput.value = '';
                    previewArea.classList.add('hidden');
                    imgPreview.src = '#';
                    fileNameEl.textContent = '';
                });
            }

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('bg-teal-50', 'border-teal-400');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('bg-teal-50', 'border-teal-400');
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('bg-teal-50', 'border-teal-400');
                const f = e.dataTransfer.files && e.dataTransfer.files[0];
                if (f) {
                    const dt = new DataTransfer();
                    dt.items.add(f);
                    fileInput.files = dt.files;
                    handleFile(f);
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAboutModal();
                closeLogoutModal();
                closeTambahKamarModal();
                // Tutup semua dropdown saat Esc
                document.getElementById('profile-menu').classList.add('hidden');
                document.getElementById('contact-dropdown').classList.add('hidden');
            }
        });
    </script>
</body>

</html>