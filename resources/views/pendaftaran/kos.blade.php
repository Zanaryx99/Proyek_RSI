<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ isset($kos) ? 'Edit' : 'Pendaftaran' }} Kos</title>

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
    </style>
</head>

<body class="min-h-screen">
    <header class="top-0 left-0 w-full bg-white shadow-sm z-10 border-b border-gray-200">
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
                <a href="#" class="text-teal-700 font-semibold">contact</a>
                <a href="#" class="text-teal-700 font-semibold">about us</a>
            </nav>
        </div>
    </header>

    <main class="pt-24 pb-12 flex justify-center">
        <div class="w-full max-w-2xl bg-white p-8 md:p-12 rounded-3xl shadow-xl">
            <!-- Header dengan tombol kembali -->
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('pemilik.dashboard') }}" class="flex items-center text-teal-600 hover:text-teal-700 transition-colors">
                    <i class='bx bx-arrow-back text-2xl mr-2'></i>
                    <span class="font-medium">Kembali</span>
                </a>
                <h2 class="text-3xl font-bold text-teal-700">
                    {{ isset($kos) ? 'Edit Kos' : 'Pendaftaran Kos' }}
                </h2>
                <div class="w-24"></div> <!-- Spacer untuk centering -->
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Error Messages -->
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

            <!-- Form akan dinamis berdasarkan mode (create/update) -->
            <form action="{{ isset($kos) ? route('kos.update', $kos->id) : route('kos.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @if(isset($kos))
                @method('PUT')
                @endif

                <div>
                    <label for="nama_kos" class="block text-sm font-medium text-gray-600 mb-1">Nama Kos</label>
                    <input type="text"
                        name="nama_kos"
                        id="nama_kos"
                        class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                        value="{{ old('nama_kos', $kos->nama_kos ?? '') }}"
                        required>
                </div>

                <div>
                    <label for="foto-upload" class="block text-sm font-medium text-gray-600 mb-1">
                        Foto Kos
                        @if(isset($kos))
                        <span class="text-xs text-gray-500">(Biarkan kosong jika tidak ingin mengubah foto)</span>
                        @endif
                    </label>

                    <!-- Preview foto lama jika mode edit -->
                    @if(isset($kos) && $kos->foto)
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $kos->foto) }}"
                            alt="Foto {{ $kos->nama_kos }}"
                            class="img-preview mx-auto border border-gray-300">
                    </div>
                    @endif

                    <div id="dropzone" class="mt-1 flex flex-col items-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-teal-400 transition-colors"
                        aria-describedby="file-upload-desc">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <div class="flex text-sm text-gray-600 items-center justify-center gap-2">
                                <button type="button" id="btn-browse" class="bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 px-3 py-1 border border-teal-300">
                                    Upload {{ isset($kos) ? 'foto baru' : 'a file' }}
                                </button>
                                <p class="pl-1 text-sm">atau taruh ke sini</p>
                            </div>

                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>

                        <input id="foto-upload"
                            name="foto"
                            type="file"
                            accept="image/*"
                            class="hidden"
                            {{ isset($kos) ? '' : 'required' }}>
                        <div id="preview-area" class="mt-4 w-full hidden">
                            <img id="img-preview" class="img-preview mx-auto border border-gray-300" src="#" alt="Preview foto" />
                            <p id="file-name" class="text-center text-sm text-gray-600 mt-2"></p>
                            <button type="button"
                                id="btn-remove-preview"
                                class="mx-auto mt-2 block text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class='bx bx-x-circle'></i> Hapus Preview
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-600 mb-1">Jenis Kos</label>
                    <select name="jenis" id="jenis" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2" required>
                        <option value="">-- Pilih Jenis Kos --</option>
                        <option value="Pria" {{ old('jenis', $kos->jenis ?? '') == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ old('jenis', $kos->jenis ?? '') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        <option value="Campur" {{ old('jenis', $kos->jenis ?? '') == 'Campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-600 mb-1">Lokasi</label>
                    <textarea name="lokasi"
                        id="lokasi"
                        rows="3"
                        class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                        required>{{ old('lokasi', $kos->lokasi ?? '') }}</textarea>
                </div>

                <div>
                    <label for="fasilitas_umum" class="block text-sm font-medium text-gray-600 mb-1">Fasilitas Umum</label>
                    <textarea name="fasilitas_umum"
                        id="fasilitas_umum"
                        rows="3"
                        class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                        required>{{ old('fasilitas_umum', $kos->fasilitas_umum ?? '') }}</textarea>
                </div>

                <div>
                    <label for="peraturan_umum" class="block text-sm font-medium text-gray-600 mb-1">Peraturan Umum</label>
                    <textarea name="peraturan_umum"
                        id="peraturan_umum"
                        rows="3"
                        class="w-full border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 px-4 py-2"
                        required>{{ old('peraturan_umum', $kos->peraturan_umum ?? '') }}</textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-teal-600 text-white font-bold py-3 px-4 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300">
                        <i class='bx {{ isset($kos) ? "bx-save" : "bx-check-circle" }}'></i>
                        {{ isset($kos) ? 'Simpan Perubahan' : 'Daftarkan' }}
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        (function() {
            const btn = document.getElementById('profile-menu-button');
            const menu = document.getElementById('profile-menu');
            if (btn && menu) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });
                document.addEventListener('click', (e) => {
                    if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }

            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('foto-upload');
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
                    return;
                }
                if (file.size > MAX_BYTES) {
                    alert('Ukuran file maksimal 2MB.');
                    fileInput.value = '';
                    return;
                }
                showPreview(file);
            }

            btnBrowse.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });

            dropzone.addEventListener('click', (e) => {
                if (e.target !== btnBrowse && !btnBrowse.contains(e.target)) {
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
        })();
    </script>
</body>

</html>