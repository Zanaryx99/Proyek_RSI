<!-- 
  File ini: resources/views/components/app-header.blade.php
  
  Variabel $user dan $profileUrl (yang berisi link profil yang benar)
  otomatis tersedia di sini dari file AppHeader.php.
-->
<header class="fixed top-0 left-0 w-full bg-white shadow-sm z-10 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

        <div class="flex items-center gap-3">

            <div class="relative">
                <button id="profile-menu-button" class="flex items-center rounded-full focus:outline-none" aria-haspopup="true" aria-expanded="false">

                    <!-- 
                      BAGIAN DINAMIS 1: FOTO PROFIL
                      Menampilkan foto user jika ada, jika tidak, tampilkan ikon default.
                    -->
                    @if ($user && $user->foto_profile)
                    <!-- Tampilkan foto profil user -->
                    <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/' . $user->foto_profile) }}" alt="Foto Profil">
                    @else
                    <!-- Tampilkan ikon default -->
                    <div class="w-10 h-10 flex items-center justify-center bg-purple-100 rounded-full">
                        <i class='bx bxs-user bx-sm text-purple-500'></i>
                    </div>
                    @endif

                </button>

                <div id="profile-menu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow py-1 ring-1 ring-black ring-opacity-5">

                    <!-- 
                      BAGIAN DINAMIS 2: LINK PROFIL
                      Variabel $profileUrl sudah berisi link yang benar
                      (ke profil.pemilik atau profil.penghuni) dari Class Component.
                    -->
                    <a href="{{ $profileUrl }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>

                    <button onclick="openLogoutModal()" class="w-full text-left px-4 py-2 text-sm text-red-600 font-semibold hover:bg-gray-100">
                        Logout
                    </button>
                </div>
            </div>

            <!-- Bagian ini statis dan akan selalu tampil -->
            <span class="text-2xl font-bold text-teal-700">Sikosan</span>

        </div>

        <nav class="flex items-center space-x-6 md:space-x-8 text-sm md:text-base">
            <!-- Contact Dropdown -->
            <div class="relative mr-4 md:mr-8">
                <button id="contact-dropdown-button" class="text-teal-700 font-semibold focus:outline-none flex items-center" type="button" aria-expanded="false" aria-controls="contact-dropdown">
                    Contact
                </button>

                <div id="contact-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl py-2 ring-1 ring-black ring-opacity-5 z-20 transition-all duration-300 transform origin-top-right">
                    <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center px-3 py-2 text-sm text-gray-800 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                        <i class='bx bxl-whatsapp text-lg mr-2 text-green-500'></i>
                        <div class="flex flex-col">
                            <span class="font-semibold">WhatsApp</span>
                            <span class="text-xs text-gray-500">+62 812-3456-7890</span>
                        </div>
                    </a>
                    <a href="https://instagram.com/sikosanapp" target="_blank" class="flex items-center px-3 py-2 text-sm text-gray-800 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                        <i class='bx bxl-instagram text-lg mr-2 text-pink-500'></i>
                        <div class="flex flex-col">
                            <span class="font-semibold">Instagram</span>
                            <span class="text-xs text-gray-500">@sikosanapp</span>
                        </div>
                    </a>
                    <a href="mailto:support@sikosan.com" class="flex items-center px-3 py-2 text-sm text-gray-800 hover:bg-teal-50 hover:text-teal-700 transition-colors">
                        <i class='bx bx-envelope text-lg mr-2 text-blue-500'></i>
                        <div class="flex flex-col">
                            <span class="font-semibold">Email</span>
                            <span class="text-xs text-gray-500">support@sikosan.com</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- About Us Button -->
            <div class="relative">
                <button onclick="openAboutModal()" class="text-teal-700 font-semibold focus:outline-none flex items-center" type="button">
                    About Us
                </button>
            </div>
        </nav>

    </div>
</header>