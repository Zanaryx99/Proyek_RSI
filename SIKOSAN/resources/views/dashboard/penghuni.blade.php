<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    {{-- TAMBAHAN: Tag meta CSRF token untuk 'fetch' JavaScript --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Star Rating Styles */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            cursor: pointer;
            font-size: 2rem;
            transition: color 0.2s ease-in-out;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
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
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-teal-800">Kos Saya</h1>
                <p class="text-gray-600 mt-2">Hai, {{ Auth::user()->nama_lengkap ?? Auth::user()->name }}!</p>
                <p class="text-gray-600">Ini kos yang lagi kamu huni</p>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
                {{ session('success') }}
            </div>
            @endif

            @foreach($kosCollection as $kos)
            <div class="kos-card mb-8">
                <div class="relative">
                    <img src="{{ $kos->foto ? asset('storage/' . $kos->foto) : asset('images/placeholder.png') }}"
                        alt="Foto {{ $kos->nama_kos }}" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                </div>

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

                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-teal-800">Kamar Anda</h3>
                                <p class="text-teal-700">{{ $kamarDitempati->nama_kamar }}</p>
                                <p class="text-sm text-teal-600">Rp
                                    {{ number_format($kamarDitempati->harga_sewa, 0, ',', '.') }}/bulan
                                </p>
                            </div>
                            <span class="bg-teal-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Terisi
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-4"> {{-- Container untuk 3 tombol aksi --}}
                        @if (isset($owner))
                        {{-- TOMBOL 1: CHAT PEMILIK (Aktif, menggunakan $owner) --}}
                        <a href="{{ route('chat', $owner) }}" class="action-button btn-primary">
                            <i class='bx bx-chat'></i>
                            Chat Pemilik
                        </a>
                        @else
                        {{-- TOMBOL 1: CHAT PEMILIK (Non-Aktif jika owner tidak ada) --}}
                        <button class="action-button btn-primary" disabled>
                            <i class='bx bx-chat'></i>
                            Chat Pemilik
                        </button>
                        @endif

                        {{-- TOMBOL 2: ULAS KOS --}}
                        <button class="action-button btn-secondary" onclick="openReviewModal()">
                            <i class='bx bx-star'></i>
                            Ulas Kos
                        </button>


                        {{-- TOMBOL 3: PEMBAYARAN (Menggunakan fungsi onclick sesuai kode referensi Anda) --}}
                        <button
                            type="button"
                            onclick="openBuktiPembayaranModal()"
                            class="action-button btn-primary">
                            <i class='bx bx-dollar'></i>
                            Pembayaran
                        </button>
                    </div>

                    {{-- Tombol AKHIRI KONTRAK tetap di luar grid dan w-full --}}
                    <div class="mt-4">
                        <button
                            type="button"
                            onclick="openEndContractModal({{ $kamarDitempati->id }})"
                            class="action-button btn-danger w-full text-lg py-3">
                            <i class='bx bx-log-out'></i>
                            AKHIRI KONTRAK
                        </button>
                    </div>

                    @if($kos->fasilitas_umum)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-3">Fasilitas Umum</h3>
                        <p class="text-gray-600">{{ $kos->fasilitas_umum }}</p>
                    </div>
                    @endif

                    @if($kos->peraturan_umum)
                    <div class="mt-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Peraturan Kos</h3>
                        <p class="text-gray-600">{{ $kos->peraturan_umum }}</p>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800 mb-3">Review Anda</h3>

                            <div class="flex items-center space-x-3">

                                <button onclick="openReviewModal('edit')" class="text-teal-600 hover:text-teal-700 focus:outline-none" title="Edit Review">
                                    <i class='bx bx-edit-alt text-2xl'></i>
                                </button>

                                <button onclick="openDeleteReviewModal()" class="text-red-500 hover:text-red-700 focus:outline-none" title="Hapus Review">
                                    <i class='bx bx-trash text-2xl'></i>
                                </button>

                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class='bx bxs-star text-xl {{ $i <= ($kamarDitempati->rating ?? 0) ? "text-yellow-400" : "text-gray-300" }}'></i>
                                    @endfor
                            </div>
                            <span class="text-sm text-gray-600">({{ $kamarDitempati->rating ?? 0 }}/5)</span>
                        </div>
                        <p class="text-gray-600">{{ $kamarDitempati->review ?? 'Belum ada review' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>
    @endif


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

    <div id="deleteReviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeDeleteReviewModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0">
                            <i class='bx bx-trash text-2xl text-red-600'></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">
                                Hapus Review
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">
                                    Apakah Anda yakin ingin menghapus review ini? Tindakan ini akan mengatur ulang rating dan review Anda menjadi kosong.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">

                    <form id="deleteReviewForm" action="{{ route('review.destroy') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors sm:w-auto sm:text-sm">
                            <i class='bx bx-trash mr-2'></i>
                            Ya, Hapus
                        </button>
                    </form>

                    <button type="button" onclick="closeDeleteReviewModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="reviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeReviewModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0">
                            <i class='bx bx-star text-2xl text-yellow-600'></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" id="reviewModalTitle">
                                Ulas Kos
                            </h3>
                            <form id="reviewForm" class="mt-4">
                                @csrf
                                <input type="hidden" name="action" id="reviewAction" value="create">
                                <div class="mb-6 px-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="rating" value="5" />
                                        <label for="star5" class="bx bxs-star"></label>
                                        <input type="radio" id="star4" name="rating" value="4" />
                                        <label for="star4" class="bx bxs-star"></label>
                                        <input type="radio" id="star3" name="rating" value="3" />
                                        <label for="star3" class="bx bxs-star"></label>
                                        <input type="radio" id="star2" name="rating" value="2" />
                                        <label for="star2" class="bx bxs-star"></label>
                                        <input type="radio" id="star1" name="rating" value="1" />
                                        <label for="star1" class="bx bxs-star"></label>
                                    </div>
                                </div>
                                <div class="mb-4 px-4">
                                    <label for="review" class="block text-sm font-semibold text-gray-700 mb-2">Review</label>
                                    <textarea id="review" name="review" rows="4" class="shadow-sm focus:ring-teal-500 focus:border-teal-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-lg px-3 py-2" placeholder="Bagikan pengalaman Anda tentang kos ini..."></textarea>
                                </div>
                                <div class="flex justify-end gap-3 px-4">
                                    <button type="button" onclick="closeReviewModal()" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:text-sm">
                                        Batal
                                    </button>
                                    <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:text-sm">
                                        <span id="submitButtonText">Upload Review</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-bukti-pembayaran" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeBuktiPembayaranModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">

                <form id="bukti-pembayaran-form" method="POST" action="{{ route('pembayaran.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 sm:mx-0">
                                <i class='bx bx-receipt text-2xl text-teal-600'></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-xl leading-6 font-bold text-gray-900">
                                    Bukti Pembayaran
                                </h3>
                            </div>
                            <button
                                type="button"
                                onclick="closeBuktiPembayaranModal()"
                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                                <i class='bx bx-x text-2xl'></i>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Upload Bukti Gambar</label>

                            <div
                                id="upload-area-bukti"
                                class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-teal-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class='bx bx-cloud-upload text-5xl text-gray-400'></i>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium text-teal-600 upload-text">
                                            Klik untuk upload atau drag & drop
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (Max. 10MB)</p>
                                </div>
                            </div>
                            <span id="error-bukti" class="text-sm text-red-500 hidden"></span>

                            <input id="bukti-pembayaran-file-input" name="bukti_pembayaran_file" type="file" class="sr-only" accept="image/*">

                            <div>
                                <label for="metode-pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <input type="text" id="metode-pembayaran" name="metode_pembayaran" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                <span id="error-metode" class="text-sm text-red-500 hidden"></span>
                            </div>

                            <div>
                                <label for="jumlah-pembayaran" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran</labeHalamanLogin,>
                                    <input type="number" id="jumlah-pembayaran" name="nominal" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                    <span id="error-jumlah" class="text-sm text-red-500 hidden"></span>
                            </div>

                            <div>
                                <label for="id-tagihan" class="block text-sm font-medium text-gray-700">ID Tagihan</label>
                                <input type="text" id="id-tagihan" name="id_tagihan" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                <span id="error-tagihan" class="text-sm text-red-500 hidden"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-teal-600 text-base font-semibold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:ml-3 sm:w-auto sm:text-sm">
                            Upload Bukti
                        </button>
                        <button
                            type="button"
                            onclick="closeBuktiPembayaranModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="endContractModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeEndContractModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0">
                            <i class='bx bx-log-out text-2xl text-red-600'></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">
                                Akhiri Kontrak
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">
                                    Apakah Anda yakin ingin mengakhiri kontrak dan keluar dari kamar ini?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <form id="endContractForm" action="{{ route('kamar.keluar', $kamarDitempati->id ?? 0) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors sm:w-auto sm:text-sm">
                            <i class='bx bx-log-out mr-2'></i>
                            Ya, Akhiri Kontrak
                        </button>
                    </form>
                    <button type="button" onclick="closeEndContractModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors sm:mt-0 sm:w-auto">
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

        // Fungsi untuk membuka modal Hapus Review
        function openDeleteReviewModal() {
            const modal = document.getElementById('deleteReviewModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        // Fungsi untuk menutup modal Hapus Review
        function closeDeleteReviewModal() {
            const modal = document.getElementById('deleteReviewModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAboutModal();
                closeLogoutModal();
                closeReviewModal();
                closeEndContractModal();
                closeDeleteReviewModal(); // <-- TAMBAHKAN BARIS INI
                // Tutup semua dropdown saat Esc
                document.getElementById('profile-menu').classList.add('hidden');
                document.getElementById('contact-dropdown').classList.add('hidden');
            }
        });

        function openEndContractModal(kamarId) {
            const modal = document.getElementById('endContractModal');
            const form = document.getElementById('endContractForm'); // Dapatkan form di dalam modal

            // 1. Atur URL Action Form
            // Sesuaikan dengan route Laravel Anda. Contoh: '/kamar/keluar/ID_KAMAR'
            // Pastikan Anda mendapatkan URL dasarnya dari Blade (misalnya di <meta> tag atau variabel JS)

            // **ASUMSI:** Base route 'kamar.keluar' adalah '/kamar/keluar/'
            // Anda harus menentukan cara mendapatkan base URL route 'kamar.keluar' di JS.

            // CARA PALING EFEKTIF: Simpan template URL di elemen HTML atau variabel global.
            // Misal: <div data-keluar-route="{{ route('kamar.keluar', 'ID_PLACEHOLDER') }}" id="route-data"></div>
            // Atau kita gunakan saja logika konstruksi URL yang paling dasar:

            // Dapatkan URL dasar (asumsi route 'kamar.keluar' adalah /kamar/keluar/{kamar})
            // NOTE: Ini akan mengabaikan nilai placeholder default '0' di Blade
            let baseUrl = form.getAttribute('action').replace(/\/\d+$/, ''); // Menghapus ID yang ada (misalnya /0)

            // Jika Anda sudah mengetahui URL dasar, Anda bisa menggunakannya.
            // Contoh: const baseUrl = '/kamar/keluar/'; // Ganti sesuai struktur route Anda

            // Gunakan URL yang sudah ada dan ganti angka terakhirnya dengan ID yang baru
            let newActionUrl = form.getAttribute('action').replace(/(\/\d+)$/, '/' + kamarId);

            form.setAttribute('action', newActionUrl); // Set Action URL yang baru

            // 2. Tampilkan Modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // 3. Tutup dropdown
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('contact-dropdown').classList.add('hidden');
        }

        // Fungsi untuk menutup modal Akhiri Kontrak
        function closeEndContractModal() {
            const modal = document.getElementById('endContractModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Event listener Escape key (pertahankan)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEndContractModal();
                // ... modal lainnya
            }
        });

        // Fungsi untuk membuka modal Review
        function openReviewModal(mode = 'create') {
            const modal = document.getElementById('reviewModal');
            const form = document.getElementById('reviewForm');
            const title = document.getElementById('reviewModalTitle');
            const submitText = document.getElementById('submitButtonText');
            const actionInput = document.getElementById('reviewAction');

            // Reset form
            if (form) form.reset();

            if (mode === 'edit') {
                title.textContent = 'Edit Review';
                submitText.textContent = 'Simpan Perubahan';
                actionInput.value = 'edit';

                // Mengisi form dengan data review yang ada
                const currentRating = parseInt('{{ $kamarDitempati->rating ?? 0 }}', 10) || 0;
                const currentReview = `{!! addslashes($kamarDitempati->review ?? '') !!}`;

                if (currentRating > 0) {
                    const starInput = document.querySelector(`input[name="rating"][value="${currentRating}"]`);
                    if (starInput) starInput.checked = true;
                }
                const reviewEl = document.getElementById('review');
                if (reviewEl) reviewEl.value = currentReview;
            } else {
                title.textContent = 'Ulas Kos';
                submitText.textContent = 'Kirim Review';
                actionInput.value = 'create';
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal Review
        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Menangani submit form review
        (function() {
            const reviewForm = document.getElementById('reviewForm');
            if (!reviewForm) return;

            reviewForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const formData = new FormData(this);
                const action = formData.get('action') || 'create';

                const url = '/review';
                let method = 'POST'; // Fetch API selalu POST

                if (action === 'edit') {
                    // Tambahkan _method spoofing untuk Laravel jika 'edit'
                    formData.append('_method', 'PUT');
                }

                try {
                    const res = await fetch(url, {
                        method: method, // Ini harus 'POST' untuk fetch
                        headers: {
                            // HAPUS 'Content-Type': 'application/json'
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                            // Jangan set Content-Type, biarkan browser
                            // mengaturnya ke 'multipart/form-data' secara otomatis
                        },
                        body: formData // Kirim objek FormData langsung
                    });

                    if (res.ok) {
                        // simplest: reload to update the review area
                        location.reload();
                    } else if (res.status === 422) {
                        const json = await res.json();
                        alert((json.message || 'Validasi error') + '\n' + JSON.stringify(json.errors || {}));
                    } else {
                        const json = await res.json().catch(() => null);
                        alert((json && (json.message || json.error)) || 'Gagal menyimpan review');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan saat mengirim review');
                }

                closeReviewModal();
            });
        })();

        const modalPembayaran = document.getElementById('modal-bukti-pembayaran');
        const uploadArea = document.getElementById('upload-area-bukti');
        const fileInput = document.getElementById('bukti-pembayaran-file-input');

        // Ambil elemen form dan input
        const formPembayaran = document.getElementById('bukti-pembayaran-form');
        const inputMetode = document.getElementById('metode-pembayaran');
        const inputJumlah = document.getElementById('jumlah-pembayaran');
        const inputTagihan = document.getElementById('id-tagihan');

        // Ambil elemen pesan error
        const errorBukti = document.getElementById('error-bukti');
        const errorMetode = document.getElementById('error-metode');
        const errorJumlah = document.getElementById('error-jumlah');
        const errorTagihan = document.getElementById('error-tagihan');


        // --- Kontrol Modal ---
        function openBuktiPembayaranModal() {
            modalPembayaran.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBuktiPembayaranModal() {
            modalPembayaran.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Reset tampilan area upload saat modal ditutup
            const fileNameDisplay = uploadArea.querySelector('.upload-text');
            fileNameDisplay.textContent = 'Klik untuk upload atau drag & drop';
            uploadArea.classList.add('border-gray-300');
            uploadArea.classList.remove('border-teal-500');

            // Reset pesan error saat modal ditutup
            errorBukti.classList.add('hidden');
            errorMetode.classList.add('hidden');
            errorJumlah.classList.add('hidden');
            errorTagihan.classList.add('hidden');
        }

        // Menutup modal jika user mengklik area abu-abu
        modalPembayaran.addEventListener('click', function(event) {
            if (event.target === modalPembayaran) {
                closeBuktiPembayaranModal();
            }
        });

        // --- Pemicu Input File & Feedback ---

        // Memicu klik pada input file saat area upload diklik
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        // Memberi feedback visual nama file yang dipilih
        fileInput.addEventListener('change', function() {
            const fileNameDisplay = uploadArea.querySelector('.upload-text');
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = 'File terpilih: ' + this.files[0].name;
                uploadArea.classList.add('border-teal-500');
                uploadArea.classList.remove('border-gray-300');
            } else {
                fileNameDisplay.textContent = 'Klik untuk upload atau drag & drop';
                uploadArea.classList.add('border-gray-300');
                uploadArea.classList.remove('border-teal-500');
            }
        });


        // =======================================================
        // ==================== FUNGSI VALIDASI ====================
        // =======================================================

        function validatePaymentForm() {
            let isValid = true;

            // 1. Reset semua pesan error
            errorBukti.classList.add('hidden');
            errorMetode.classList.add('hidden');
            errorJumlah.classList.add('hidden');
            errorTagihan.classList.add('hidden');

            // 2. Cek Bukti Pembayaran (File Input)
            if (fileInput.files.length === 0) {
                errorBukti.textContent = '❗ Bukti gambar transfer wajib di-upload.';
                errorBukti.classList.remove('hidden');
                isValid = false;
            }

            // 3. Cek Metode Pembayaran
            if (inputMetode.value.trim() === '') {
                inputMetode.classList.add('border-red-500'); // Opsional: Beri border merah
                errorMetode.textContent = '❗ Metode pembayaran harus diisi.';
                errorMetode.classList.remove('hidden');
                isValid = false;
            } else {
                inputMetode.classList.remove('border-red-500');
            }

            // 4. Cek Jumlah Pembayaran (harus diisi dan > 0)
            if (inputJumlah.value.trim() === '' || parseFloat(inputJumlah.value) <= 0) {
                inputJumlah.classList.add('border-red-500');
                errorJumlah.textContent = '❗ Masukkan jumlah pembayaran yang valid (lebih dari 0).';
                errorJumlah.classList.remove('hidden');
                isValid = false;
            } else {
                inputJumlah.classList.remove('border-red-500');
            }

            // 5. Cek ID Tagihan
            if (inputTagihan.value.trim() === '') {
                inputTagihan.classList.add('border-red-500');
                errorTagihan.textContent = '❗ ID Tagihan wajib diisi.';
                errorTagihan.classList.remove('hidden');
                isValid = false;
            } else {
                inputTagihan.classList.remove('border-red-500');
            }

            return isValid;
        }


        // --- EVENT LISTENER UNTUK SUBMIT FORM ---
        formPembayaran.addEventListener('submit', function(event) {
            // Jalankan fungsi validasi
            if (!validatePaymentForm()) {
                // Jika validasi gagal (mengembalikan false), hentikan pengiriman form
                event.preventDefault();
            }
        });
    </script>
</body>

</html>