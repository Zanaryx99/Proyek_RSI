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
    <x-header />

    <main class="max-w-screen-xl mx-auto mt-20 p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <a href="{{ route('pemilik.dashboard') }}"
                class="flex items-center text-teal-600 hover:text-teal-700 transition-colors">
                <i class='bx bx-arrow-back text-2xl mr-2'></i>
                <span class="font-medium">Kembali</span>
            </a>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <div class="relative">
                    <img src="{{ $kos->foto ? asset('storage/' . $kos->foto) : 'https://images.unsplash.com/photo-1585098944543-9b57827238fb?q=80&w=2070' }}"
                        alt="Foto Kost" class="w-full h-60 object-cover rounded-lg">
                    <button
                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-2 hover:bg-white"><i
                            class='bx bx-chevron-left text-2xl'></i></button>
                    <button
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-2 hover:bg-white"><i
                            class='bx bx-chevron-right text-2xl'></i></button>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">{{ $kos->nama_kos ?? 'Nama Kost' }}</h2>
                <p class="text-sm text-gray-500">Jenis Kos: {{ $kos->jenis ?? '-' }}</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                <div>
                    <h3 class="font-bold text-gray-800">Lokasi</h3>
                    <p class="text-gray-600 text-sm">{{ $kos->lokasi ?? 'Lokasi belum diisi.' }}</p>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Fasilitas Umum</h3>
                    <ul class="text-gray-600 text-sm space-y-1">
                        @if(!empty($kos->fasilitas_umum))
                        @foreach(preg_split("/\r\n|\n|\r/", $kos->fasilitas_umum) as $f)
                        @if(trim($f) !== '')
                        <li class="flex items-center"><i class='bx bx-check-circle custom-icon mr-2'></i>{{ $f }}</li>
                        @endif
                        @endforeach
                        @else
                        <li class="text-gray-500">Belum ada fasilitas umum yang didaftarkan.</li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Peraturan Umum Kos</h3>
                    <ul class="text-gray-600 text-sm space-y-1 list-disc list-inside">
                        @if(!empty($kos->peraturan_umum))
                        @foreach(preg_split("/\r\n|\n|\r/", $kos->peraturan_umum) as $peraturan)
                        @if(trim($peraturan) !== '')
                        <li>{{ $peraturan }}</li>
                        @endif
                        @endforeach
                        @else
                        <li class="list-none text-gray-500">Belum ada peraturan umum yang didaftarkan.</li>
                        @endif
                    </ul>
                </div>

                <a href="{{ route('kamar.index', $kos->id) }}"
                    class="block w-full mt-4 py-2 px-4 bg-teal-50 text-teal-700 font-semibold rounded-lg hover:bg-teal-100 transition-colors text-center">
                    <i class='bx bx-plus-circle mr-1'></i> Kelola Kamar
                </a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="font-bold text-gray-800">Rating</h3>
                <div class="flex items-center mt-1">
                    @php
                    // Prioritaskan avgRating (dihitung dari review kamar). Jika tidak ada, fallback ke $rating dari model kos.
                    $displayRating = isset($avgRating) && $avgRating ? $avgRating : ($rating ? round($rating * 2) / 2 : null);
                    $fullStars = $displayRating ? floor($displayRating) : 0;
                    $halfStar = $displayRating && ($displayRating - $fullStars) >= 0.5;
                    $reviewsCount = isset($reviews) ? count($reviews) : 0;
                    @endphp

                    <button id="openRatingsModalBtn" class="flex items-center p-0 m-0 text-left hover:underline focus:outline-none">
                        @for($i=0; $i < $fullStars; $i++)
                            <i class='bx bxs-star text-yellow-500'></i>
                        @endfor
                        @if($halfStar)
                            <i class='bx bxs-star-half text-yellow-500'></i>
                        @endif
                        @for($i = $fullStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                            <i class='bx bxs-star text-gray-300'></i>
                        @endfor

                        <span class="ml-2 text-sm font-semibold text-gray-700">
                            {{ $displayRating ? $displayRating . '/5' : 'Belum ada penilaian' }}
                            @if($reviewsCount > 0)
                                <span class="text-xs text-gray-500">({{ $reviewsCount }} review)</span>
                            @endif
                        </span>
                    </button>
                </div>
            </div>

            <!-- Ratings Modal (pemilik melihat ringkasan + daftar review) -->
            <div id="ratingsModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeRatingsModal()"></div>

                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0">
                                    <i class='bx bxs-star text-2xl text-yellow-500'></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-xl leading-6 font-bold text-gray-900">Rating & Review untuk {{ $kos->nama_kos }}</h3>
                                    <p class="text-sm text-gray-600 mt-2">Ringkasan perhitungan dan semua review yang dikirim oleh penghuni.</p>

                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-2xl font-bold text-gray-800">{{ $displayRating ? $displayRating . '/5' : 'Belum ada penilaian' }}</p>
                                                <p class="text-sm text-gray-500">Berdasarkan {{ $reviewsCount }} review</p>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{-- Jika perlu tambahkan breakdown distribusi rating di sini --}}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Daftar review individual --}}
                                    <div class="mt-4 max-h-72 overflow-y-auto space-y-3">
                                        @forelse($reviews ?? collect() as $r)
                                            <div class="p-3 bg-white border rounded-lg">
                                                <div class="flex items-start gap-3">
                                                    <img class="w-10 h-10 rounded-full" src="{{ isset($r['user']) && $r['user'] && $r['user']->foto_profile ? asset('storage/' . $r['user']->foto_profile) : 'https://i.pravatar.cc/40?u=' . ($r['user_id'] ?? 'guest') }}" alt="avatar">
                                                    <div class="flex-1">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="font-medium text-gray-900">{{ isset($r['user']) && $r['user'] ? $r['user']->nama_lengkap : 'Penghuni' }}</p>
                                                                <p class="text-xs text-gray-500">Kamar: {{ $r['nama_kamar'] }}</p>
                                                            </div>
                                                            <div class="text-sm text-yellow-500 font-semibold">{{ $r['rating'] }}/5</div>
                                                        </div>
                                                        <p class="text-gray-700 mt-2">{{ $r['review'] ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-gray-500">Belum ada review dari penghuni.</div>
                                        @endforelse
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="closeRatingsModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-teal-600 text-base font-semibold text-white hover:bg-teal-700 focus:outline-none sm:w-auto sm:text-sm">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <button
                class="w-full mt-4 py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                Buat Pengumuman
            </button>
        </div>

        </div>

        <div class="lg:col-span-2 mt-20 space-y-6">

            <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
    <h2 class="text-xl font-bold mb-4">Rincian Pemasukan Bulan Ini</h2>

    <div class="space-y-4">
        
        <div class="grid grid-cols-4 font-semibold text-gray-600 border-b pb-2">
            <div class="col-span-1">TANGGAL BAYAR</div>
            <div class="col-span-1">METODE</div>
            <div class="col-span-1 text-right">NOMINAL</div>
            <div class="col-span-1 text-center">BUKTI</div>
        </div>

        @forelse ($pemasukanBulanIni as $pembayaran)
            <div class="grid grid-cols-4 items-center py-2 border-b last:border-b-0">
                <div class="col-span-1 text-sm text-gray-800">
                    {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y') }}
                </div>
                <div class="col-span-1 text-sm text-gray-600">
                    {{ $pembayaran->metode_pembayaran }}
                </div>
                <div class="col-span-1 text-right font-medium text-green-600">
                    {{ 'Rp ' . number_format($pembayaran->nominal, 0, ',', '.') }}
                </div>
                <div class="col-span-1 text-center">
                    <button 
    onclick="openPreviewModal('{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}')"
    class="text-xs font-semibold text-teal-600 hover:text-teal-800 focus:outline-none"
>
    Lihat
</button>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-gray-500">
                <p>Tidak ada transaksi pembayaran tercatat bulan ini.</p>
                </div>
        @endforelse
        
        <div class="flex justify-between items-center pt-4 font-bold border-t border-gray-300">
            <div class="text-lg">Total Pemasukan</div>
            <div class="text-lg text-green-700">
                {{ 'Rp ' . number_format($totalPemasukanPembayaran, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

            <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
    <h2 class="text-xl font-bold mb-4">Tagihan Bulan Lalu</h2>

    <div class="space-y-4">
        
        <div class="grid grid-cols-5 font-semibold text-gray-600 border-b pb-2">
            <div class="col-span-1">PENGHUNI (KAMAR)</div>
            <div class="col-span-1">JATUH TEMPO</div>
            <div class="col-span-1 text-right">NOMINAL BAYAR</div>
            <div class="col-span-1 text-center">STATUS</div>
            <div class="col-span-1 text-right">TAGIHAN POKOK</div>
        </div>

        @php
            $totalBelumLunas = 0;
        @endphp

        @forelse ($tagihanBulanLalu as $tagihan)
            @php
                // Hitung total yang Belum Lunas
                if ($tagihan['status'] !== 'LUNAS') {
                    $totalBelumLunas += $tagihan['harga_sewa'];
                }
            @endphp
            
            <div class="grid grid-cols-5 items-center py-2 border-b last:border-b-0">
                <div class="col-span-1 text-sm text-gray-800">
                    {{ $tagihan['nama_penghuni'] }} ({{ $tagihan['nama_kamar'] }})
                </div>
                <div class="col-span-1 text-sm text-gray-600">
                    {{ $tagihan['jatuh_tempo'] }}
                </div>
                <div class="col-span-1 text-sm text-right font-medium 
                    {{ $tagihan['status'] === 'LUNAS' ? 'text-green-600' : 'text-red-600' }}">
                    {{ 'Rp ' . number_format($tagihan['nominal_bayar'], 0, ',', '.') }}
                    @if ($tagihan['selisih'] > 0)
                        <br><span class="text-xs text-red-500">(Kurang: Rp {{ number_format($tagihan['selisih'], 0, ',', '.') }})</span>
                    @endif
                </div>
                <div class="col-span-1 text-center">
                    @if ($tagihan['status'] === 'LUNAS')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">LUNAS</span>
                    @elseif ($tagihan['status'] === 'BELUM BAYAR')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">BELUM BAYAR</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">KURANG</span>
                    @endif
                </div>
                <div class="col-span-1 text-right font-bold">
                    {{ 'Rp ' . number_format($tagihan['harga_sewa'], 0, ',', '.') }}
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-gray-500">Tidak ada kamar terisi yang perlu ditagih bulan lalu.</div>
        @endforelse
        
        <div class="flex justify-between items-center pt-4 font-bold border-t border-gray-300">
            <div class="text-lg">Total Tagihan Belum Lunas</div>
            <div class="text-lg text-red-700">
                {{ 'Rp ' . number_format($totalBelumLunas, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>
            <div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Penghuni Kos</h2>

    <div class="space-y-3">
    {{-- Gunakan @forelse untuk looping data kamarDihuni --}}
    @forelse ($kamarDihuni as $kamar)
    {{-- Lakukan pengecekan ketat apakah relasi user ada sebelum menampilkan data --}}
    @if ($kamar->user)
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
            <div class="flex items-center">
                {{-- Perbaikan: Pengecekan Foto Profil --}}
                @php
                    $foto_profile = $kamar->user->foto_profile 
                                  ? asset('storage/' . $kamar->user->foto_profile) 
                                  : 'https://i.pravatar.cc/40?u=' . $kamar->user_id;
                @endphp

                <img class="w-10 h-10 rounded-full mr-3"
                    src="{{ $foto_profile }}"
                    alt="{{ $kamar->user->nama_lengkap ?? 'Avatar Penghuni' }}">
                <div>
                    {{-- Tampilkan nama penghuni melalui relasi user --}}
                    <p class="font-medium text-gray-900">{{ $kamar->user->nama_lengkap }}</p>
                    {{-- Tampilkan nama/nomor kamar --}}
                    <p class="text-xs text-gray-500">Menempati: {{ $kamar->nama_kamar ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                
                {{-- PERUBAHAN DI SINI: Tombol Kirim Pesan menjadi Link Chat --}}
                <a 
                    href="{{ route('chat', $kamar->user) }}" 
                    wire:navigate
                    class="p-2 rounded-full hover:bg-gray-200 inline-flex items-center justify-center" 
                    title="Kirim Pesan ke {{ $kamar->user->nama_lengkap ?? 'Penghuni' }}"
                >
                    <i class='bx bx-message-square-dots text-xl text-gray-600'></i>
                </a>
                
                {{-- Tombol Lihat Profil (Pastikan data-* aman dari null) --}}
                <button
                    class="p-2 rounded-full hover:bg-gray-200 profile-view-btn"
                    title="Lihat Profil"
                    onclick="openProfileModal(this)"
                    data-name="{{ $kamar->user->nama_lengkap ?? 'Penghuni' }}"
                    data-phone="{{ $kamar->user->no_telepon ?? '-' }}"
                    data-email="{{ $kamar->user->email ?? '-' }}"
                    data-foto="{{ $foto_profile }}" {{-- Gunakan variabel yang sudah dibersihkan --}}
                    data-kamar="{{ $kamar->nama_kamar ?? '' }}"
                    data-gender="{{ $kamar->user->jenis_kelamin ?? '-' }}"
                >
                    <i class='bx bx-search-alt-2 text-xl text-gray-600'></i>
                </button>
            </div>
        </div>
    @else
        {{-- Tambahkan penanda jika kamar terisi tetapi data user hilang/error --}}
        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
            <p class="font-medium text-red-700">Data Penghuni Error (Kamar {{ $kamar->nama_kamar ?? 'N/A' }})</p>
        </div>
    @endif
    @empty
    {{-- Bagian ini akan tampil jika tidak ada kamarDihuni sama sekali --}}
    <div class="text-center py-4 text-gray-500">
        <p>Belum ada penghuni saat ini.</p>
    </div>
    @endforelse
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

    <!-- Profile Modal (untuk melihat profil penghuni) -->
    <div id="profileModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeProfileModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <img id="profileModalFoto" class="w-20 h-20 rounded-full mr-4" src="https://i.pravatar.cc/100" alt="avatar">
                        <div class="mt-3 text-left w-full">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" id="profileModalName">Nama Penghuni</h3>
                            <p class="text-sm text-gray-600 mt-1"><strong>Kamar      :</strong> <span id="profileModalKamar">-</span></p>
                            <p class="text-sm text-gray-600 mt-1"><strong>Gender     :</strong> <span id="profileModalGender">-</span></p>
                            <p class="text-sm text-gray-600 mt-1"><strong>Email :</strong> <span id="profileModalEmail">-</span></p>
                            <p class="text-sm text-gray-600 mt-1"><strong>Telepon :</strong> <span id="profileModalPhone">-</span></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeProfileModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-teal-600 text-base font-semibold text-white hover:bg-teal-700 focus:outline-none sm:w-auto sm:text-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</body>

<!--modal untuk melihat bukti pembayaran-->
<div id="preview-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-gray-900 bg-opacity-90 transition-opacity" onclick="closePreviewModal()"></div>

        <div class="inline-block align-middle rounded-lg text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            
            <div class="relative bg-white rounded-lg p-1">
                <button 
                    type="button" 
                    onclick="closePreviewModal()" 
                    class="absolute top-2 right-2 text-white bg-gray-800 bg-opacity-70 hover:bg-opacity-100 p-1.5 rounded-full z-10 focus:outline-none"
                >
                    <i class='bx bx-x text-xl'></i>
                </button>

                <img id="bukti-image-preview" src="" alt="Bukti Pembayaran" class="w-full h-auto max-h-[80vh] object-contain rounded-lg">
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

        // Pastikan semua dropdown tertutup
        document.getElementById('profile-menu').classList.add('hidden');
        document.getElementById('contact-dropdown').classList.add('hidden');
    }

    // Fungsi untuk menutup modal About Us
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

    // Ratings modal (pemilik melihat ringkasan & daftar review)
    function openRatingsModal() {
        const modal = document.getElementById('ratingsModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // tutup dropdown lain
        const profile = document.getElementById('profile-menu');
        const contact = document.getElementById('contact-dropdown');
        if (profile) profile.classList.add('hidden');
        if (contact) contact.classList.add('hidden');
    }

    function closeRatingsModal() {
        const modal = document.getElementById('ratingsModal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAboutModal();
            closeLogoutModal();
            closeDeleteModal();
            closeRatingsModal();
            // Tutup semua dropdown saat Esc
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('contact-dropdown').classList.add('hidden');
        }
    });

    // Pasang listener on-click untuk tombol buka rating jika ada
    document.addEventListener('DOMContentLoaded', function() {
        const ratingsBtn = document.getElementById('openRatingsModalBtn');
        if (ratingsBtn) {
            ratingsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openRatingsModal();
            });
        }
    });

    // ---------------- Profile Modal for penghuni ----------------
    function openProfileModal(btnOrEl) {
        if (!btnOrEl) return;
        // If called with event or element
        const el = btnOrEl instanceof Element ? btnOrEl : (btnOrEl.target || btnOrEl.srcElement);
        const name = el.dataset.name || 'Penghuni';
        const phone = el.dataset.phone || '-';
        const email = el.dataset.email || '-';
        const foto = el.dataset.foto || 'https://i.pravatar.cc/100';
        const kamar = el.dataset.kamar || '-';
    const gender = (el.dataset.gender || '-');

        const modal = document.getElementById('profileModal');
        if (!modal) return;

        // Fill modal fields
        const img = modal.querySelector('#profileModalFoto');
        const nm = modal.querySelector('#profileModalName');
        const ph = modal.querySelector('#profileModalPhone');
        const em = modal.querySelector('#profileModalEmail');
        const km = modal.querySelector('#profileModalKamar');
        const gd = modal.querySelector('#profileModalGender');

        if (img) img.src = foto;
        if (nm) nm.textContent = name;
        if (ph) ph.textContent = phone;
        if (em) em.textContent = email;
        if (km) km.textContent = kamar;
        // Translate stored gender code to human readable label
        if (gd) {
            let genderLabel = '-';
            // Accept both uppercase and lowercase, and allow full words to pass through
            const g = String(gender).trim();
            if (g === 'L' || g === 'l') {
                genderLabel = 'Laki-laki';
            } else if (g === 'P' || g === 'p') {
                genderLabel = 'Perempuan';
            } else if (g.toLowerCase() === 'laki-laki' || g.toLowerCase() === 'laki lelaki' || g.toLowerCase() === 'laki') {
                genderLabel = 'Laki-laki';
            } else if (g.toLowerCase() === 'perempuan' || g.toLowerCase() === 'p') {
                genderLabel = 'Perempuan';
            } else if (g === '-' || g === '') {
                genderLabel = '-';
            } else {
                // fallback: use raw value
                genderLabel = g;
            }

            gd.textContent = genderLabel;
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeProfileModal() {
        const modal = document.getElementById('profileModal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    const previewModal = document.getElementById('preview-modal');
    const buktiImagePreview = document.getElementById('bukti-image-preview');

    /**
     * Membuka modal preview gambar
     * @param {string} imageUrl - URL gambar bukti pembayaran
     */
    function openPreviewModal(imageUrl) {
        // Set sumber gambar
        buktiImagePreview.src = imageUrl;
        
        // Tampilkan modal
        previewModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    /**
     * Menutup modal preview
     */
    function closePreviewModal() {
        previewModal.classList.add('hidden');
        document.body.style.overflow = 'auto'; 
        // Bersihkan sumber gambar setelah ditutup (opsional)
        buktiImagePreview.src = '';
    }

    // Menutup modal jika user mengklik overlay
    previewModal.addEventListener('click', function(event) {
        if (event.target === previewModal) {
            closePreviewModal();
        }
    });
</script>

</html>