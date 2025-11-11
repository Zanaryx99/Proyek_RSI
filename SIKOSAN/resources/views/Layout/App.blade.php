<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Hapus duplikasi font/CSS di sini, biarkan hanya yang diperlukan --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    {{-- Sisanya adalah font yang panjang, tetapi pastikan tidak ada duplikasi @import "tailwindcss"; --}}
    
    @livewireStyles
    <title>@yield('title', 'Chat App')</title>
    @stack('css')
</head>

<body class="bg-gray-100 flex min-h-screen">

    {{-- KONTEN UTAMA: Livewire akan dimasukkan di sini --}}
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    {{-- Skrip, harus diletakkan sebelum penutup body --}}
    @livewireScripts
    @stack('scripts')
    
    {{-- Hapus duplikasi <script src="{{ asset('js/app.js') }}"></script> jika sudah ada di @vite('resources/js/app.js') --}}
    
</body>
</html>