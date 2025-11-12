<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Detail Kamar Kos - Sikosan</title>

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

        .img-preview {
            max-height: 160px;
            max-width: 100%;
            object-fit: cover;
            border-radius: .5rem;
        }

        .form-field {
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-tersedia {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-terisi {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .status-renovasi {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .info-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
            border: 1px solid #bae6fd;
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .kode-unik-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: 1rem;
            padding: 1.5rem;
        }
    </style>
</head>

<body class="min-h-screen">
    <x-header />


    <main class="pt-24 pb-12 flex justify-center">
        <div class="w-full max-w-2xl bg-white p-8 md:p-12 rounded-3xl shadow-xl">
            <!-- Header dengan tombol kembali dan edit -->
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('kamar.index', $kos->id) }}"
                    class="flex items-center text-teal-600 hover:text-teal-700 transition-colors">
                    <i class='bx bx-arrow-back text-2xl mr-2'></i>
                    <span class="font-medium">Kembali</span>
                </a>

                <h2 class="text-3xl font-bold text-teal-700 text-center flex-1">
                    Detail Kamar
                </h2>

                <!-- Tombol Edit di kanan atas -->
                <button onclick="openEditModal()"
                    class="flex items-center bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition-colors">
                    <i class='bx bx-edit-alt mr-2'></i>
                    Edit
                </button>
            </div>

            <!-- Menampilkan pesan sukses atau error -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Data Kamar (Read-only) -->
            <div class="space-y-6">
                <!-- Foto Kamar -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Foto Kamar
                    </label>
                    <div class="mt-1">
                        @if($kamar->foto_kamar)
                        <img src="{{ asset('storage/' . $kamar->foto_kamar) }}" alt="Foto {{ $kamar->nama_kamar }}"
                            class="w-full h-64 object-cover rounded-lg border border-gray-300">
                        @else
                        <div
                            class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-300">
                            <i class='bx bx-image text-4xl text-gray-400'></i>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Nama Kamar -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Kamar</label>
                    <div class="form-field">
                        {{ $kamar->nama_kamar }}
                    </div>
                </div>

                <!-- Status Ketersediaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status Ketersediaan</label>
                    <div class="form-field">
                        @if($kamar->status === 'tersedia')
                        <span class="status-badge status-tersedia">
                            <i class='bx bx-check-circle'></i>
                            Tersedia
                        </span>
                        <p class="text-sm text-gray-600 mt-2">Kamar siap untuk ditempati oleh penghuni baru.</p>
                        @elseif($kamar->status === 'terisi')
                        <span class="status-badge status-terisi">
                            <i class='bx bx-user-check'></i>
                            Terisi
                        </span>
                        <p class="text-sm text-gray-600 mt-2">Kamar sedang ditempati oleh penghuni.</p>
                        @elseif($kamar->status === 'renovasi')
                        <span class="status-badge status-renovasi">
                            <i class='bx bx-wrench'></i>
                            Renovasi
                        </span>
                        <p class="text-sm text-gray-600 mt-2">Kamar sedang dalam proses perbaikan atau renovasi.</p>
                        @endif
                    </div>
                </div>

                <!-- Tipe Kamar -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tipe Kamar</label>
                    <div class="form-field">
                        {{ $kamar->tipe_kamar ?? 'Standard' }}
                    </div>
                </div>

                <!-- Harga Sewa -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Harga Sewa per Bulan</label>
                    <div class="form-field">
                        Rp {{ number_format($kamar->harga_sewa, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Minimal Waktu Sewa -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Minimal Waktu Sewa (bulan)</label>
                    <div class="form-field">
                        {{ $kamar->minimal_waktu_sewa }} bulan
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Kamar</label>
                    <div class="form-field min-h-[100px]">
                        {{ $kamar->deskripsi ?? 'Tidak ada deskripsi' }}
                    </div>
                </div>
            </div>

            <!-- Kode Unik Kamar -->
            <div class="kode-unik-box mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class='bx bx-key text-green-500'></i>
                    Kode Unik Kamar
                </h3>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <code
                                class="text-2xl font-bold text-teal-700 bg-white px-4 py-2 rounded-lg border-2 border-teal-200">
                                {{ $kamar->kode_unik }}
                            </code>
                            <button onclick="copyKodeUnik('{{ $kamar->kode_unik }}')"
                                class="text-teal-600 hover:text-teal-700 transition-colors p-2 rounded-lg hover:bg-teal-50"
                                title="Salin kode unik">
                                <i class='bx bx-copy text-xl'></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            @if($kamar->status === 'tersedia')
                            Berikan kode ini kepada calon penghuni untuk bergabung ke kamar ini.
                            @elseif($kamar->status === 'terisi')
                            Kode unik sedang digunakan oleh penghuni.
                            @else
                            Kode unik tidak aktif saat kamar dalam renovasi.
                            @endif
                        </p>
                    </div>
                </div>
            </div>


        </div>
    </main>
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
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Edit Kamar -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-teal-700">Edit Data Kamar</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <!-- Form Edit -->
                    <form action="{{ route('kamar.update', $kamar->id) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Foto Kamar -->
                        <div>
                            <label for="edit_foto_kamar" class="block text-sm font-medium text-gray-600 mb-1">
                                Foto Kamar
                            </label>
                            <div id="edit_dropzone"
                                class="mt-1 flex flex-col items-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-teal-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    @if($kamar->foto_kamar)
                                    <img id="edit_current_photo" src="{{ asset('storage/' . $kamar->foto_kamar) }}"
                                        alt="Foto saat ini" class="w-32 h-32 object-cover rounded-lg mx-auto mb-4">
                                    @else
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    @endif

                                    <div class="flex text-sm text-gray-600 items-center justify-center gap-2">
                                        <button type="button" id="edit_btn_browse"
                                            class="bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 px-3 py-1 border border-teal-300">
                                            Ganti Foto
                                        </button>
                                        <p class="pl-1 text-sm">atau taruh ke sini</p>
                                    </div>

                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>

                                <input id="edit_foto_kamar" name="foto_kamar" type="file" accept="image/*"
                                    class="hidden">

                                <div id="edit_preview_area" class="mt-4 w-full hidden">
                                    <img id="edit_img_preview" class="img-preview mx-auto border border-gray-300"
                                        src="#" alt="Preview foto baru" />
                                    <p id="edit_file_name" class="text-center text-sm text-gray-600 mt-2"></p>
                                    <button type="button" id="edit_btn_remove_preview"
                                        class="mx-auto mt-2 block text-red-600 hover:text-red-700 text-sm font-medium">
                                        <i class='bx bx-x-circle'></i> Hapus Preview
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Kamar -->
                        <div>
                            <label for="edit_nama_kamar" class="block text-sm font-medium text-gray-600 mb-1">Nama Kamar
                                *</label>
                            <input type="text" name="nama_kamar" id="edit_nama_kamar"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                value="{{ $kamar->nama_kamar }}" placeholder="Contoh: Kamar A1, Kamar Deluxe 101"
                                required>
                        </div>

                        <!-- Status Kamar - HANYA tersedia dan renovasi -->
                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-gray-600 mb-1">Status
                                Ketersediaan *</label>
                            <select name="status" id="edit_status" required
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2">
                                <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>Tersedia
                                </option>
                                <option value="renovasi" {{ $kamar->status == 'renovasi' ? 'selected' : '' }}>Renovasi
                                </option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Status "Terisi" akan otomatis aktif ketika penghuni menggunakan kode unik
                            </p>
                        </div>

                        <!-- Tipe Kamar -->
                        <div>
                            <label for="edit_tipe_kamar" class="block text-sm font-medium text-gray-600 mb-1">Tipe
                                Kamar</label>
                            <select name="tipe_kamar" id="edit_tipe_kamar"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2">
                                <option value="">-- Pilih Tipe Kamar --</option>
                                <option value="Standard" {{ $kamar->tipe_kamar == 'Standard' ? 'selected' : '' }}>Standard
                                </option>
                                <option value="Deluxe" {{ $kamar->tipe_kamar == 'Deluxe' ? 'selected' : '' }}>Deluxe
                                </option>
                                <option value="Premium" {{ $kamar->tipe_kamar == 'Premium' ? 'selected' : '' }}>Premium
                                </option>
                                <option value="Eksekutif" {{ $kamar->tipe_kamar == 'Eksekutif' ? 'selected' : '' }}>
                                    Eksekutif</option>
                            </select>
                        </div>

                        <!-- Harga Sewa -->
                        <div>
                            <label for="edit_harga_sewa" class="block text-sm font-medium text-gray-600 mb-1">Harga Sewa
                                per Bulan *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="text" name="harga_sewa" id="edit_harga_sewa"
                                    class="w-full pl-10 border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                    value="{{ number_format($kamar->harga_sewa, 0, ',', '.') }}" placeholder="0"
                                    oninput="formatCurrency(this)" required>
                            </div>
                        </div>

                        <!-- Minimal Waktu Sewa -->
                        <div>
                            <label for="edit_minimal_waktu_sewa"
                                class="block text-sm font-medium text-gray-600 mb-1">Minimal Waktu Sewa (bulan)
                                *</label>
                            <input type="number" name="minimal_waktu_sewa" id="edit_minimal_waktu_sewa" min="1" max="24"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                value="{{ $kamar->minimal_waktu_sewa }}" required>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="edit_deskripsi" class="block text-sm font-medium text-gray-600 mb-1">Deskripsi
                                Kamar</label>
                            <textarea name="deskripsi" id="edit_deskripsi" rows="4"
                                class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                                placeholder="Deskripsikan fasilitas dan keunggulan kamar ini...">{{ $kamar->deskripsi }}</textarea>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-4 flex gap-3">
                            <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                Batal
                            </button>

                            <button type="submit"
                                class="flex-1 bg-teal-600 text-white font-bold py-3 px-4 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300">
                                <i class='bx bx-check-circle'></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
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
        // Modal functions
        function openEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Dropdown functionality & File upload functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown functionality
            const profileBtn = document.getElementById('profile-menu-button');
            const profileMenu = document.getElementById('profile-menu');
            const contactBtn = document.getElementById('contact-dropdown-button');
            const contactMenu = document.getElementById('contact-dropdown');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                    contactMenu.classList.add('hidden');
                });
            }

            if (contactBtn && contactMenu) {
                contactBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    contactMenu.classList.toggle('hidden');
                    profileMenu.classList.add('hidden');
                });
            }

            document.addEventListener('click', () => {
                profileMenu.classList.add('hidden');
                contactMenu.classList.add('hidden');
            });

            // File upload functionality for edit modal
            const editDropzone = document.getElementById('edit_dropzone');
            const editFileInput = document.getElementById('edit_foto_kamar');
            const editBtnBrowse = document.getElementById('edit_btn_browse');
            const editBtnRemovePreview = document.getElementById('edit_btn_remove_preview');
            const editPreviewArea = document.getElementById('edit_preview_area');
            const editImgPreview = document.getElementById('edit_img_preview');
            const editFileNameEl = document.getElementById('edit_file_name');
            const editCurrentPhoto = document.getElementById('edit_current_photo');
            const MAX_BYTES = 2 * 1024 * 1024;

            function showEditPreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editImgPreview.src = e.target.result;
                    editPreviewArea.classList.remove('hidden');
                    // Sembunyikan foto lama saat preview foto baru ditampilkan
                    if (editCurrentPhoto) {
                        editCurrentPhoto.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
                editFileNameEl.textContent = file.name;
            }

            function handleEditFile(file) {
                if (!file) return;
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar (PNG/JPG/GIF).');
                    editFileInput.value = '';
                    editPreviewArea.classList.add('hidden');
                    return;
                }
                if (file.size > MAX_BYTES) {
                    alert('Ukuran file maksimal 2MB.');
                    editFileInput.value = '';
                    editPreviewArea.classList.add('hidden');
                    return;
                }
                showEditPreview(file);
            }

            editBtnBrowse.addEventListener('click', (e) => {
                e.stopPropagation();
                editFileInput.click();
            });

            editDropzone.addEventListener('click', (e) => {
                if (e.target !== editBtnBrowse && !editBtnBrowse.contains(e.target) && e.target !== editBtnRemovePreview && !editBtnRemovePreview.contains(e.target)) {
                    editFileInput.click();
                }
            });

            editFileInput.addEventListener('change', (e) => {
                const f = e.target.files && e.target.files[0];
                handleEditFile(f);
            });

            // Remove preview button
            if (editBtnRemovePreview) {
                editBtnRemovePreview.addEventListener('click', (e) => {
                    e.stopPropagation();

                    editFileInput.value = '';
                    editPreviewArea.classList.add('hidden');
                    editImgPreview.src = '#';
                    editFileNameEl.textContent = '';

                    // Tampilkan kembali foto lama
                    if (editCurrentPhoto) {
                        editCurrentPhoto.classList.remove('hidden');
                    }
                });
            }

            editDropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                editDropzone.classList.add('bg-teal-50', 'border-teal-400');
            });

            editDropzone.addEventListener('dragleave', () => {
                editDropzone.classList.remove('bg-teal-50', 'border-teal-400');
            });

            editDropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                editDropzone.classList.remove('bg-teal-50', 'border-teal-400');
                const f = e.dataTransfer.files && e.dataTransfer.files[0];
                if (f) {
                    const dt = new DataTransfer();
                    dt.items.add(f);
                    editFileInput.files = dt.files;
                    handleEditFile(f);
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEditModal();
                }
            });
        });
    </script>
</body>

</html>