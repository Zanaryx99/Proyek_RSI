<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sikosan - Detail Akun</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', 'Inter', sans-serif;
            background: #f0f4f8;
            padding-top: 64px;
            margin: 0;
        }

        .rounded-card {
            border: 2px solid #dbe2ea;
            border-radius: 1.5rem;
        }

        .profile-container {
            width: 100%;
            max-width: 800px;
            padding: 40px 20px;
            margin: 0 auto;
        }

        .profile-card {
            background-color: #f7f9fb;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .edit-button {
            position: absolute;
            top: 30px;
            right: 30px;
            background: #335d7b;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.2s;
        }

        .edit-button:hover {
            background: #2a4d68;
        }

        .back-link {
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 500;
            color: #335d7b;
            text-decoration: none;
            margin-bottom: 25px;
        }

        .back-link span {
            margin-left: 10px;
        }

        .header-content {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background-color: #c9e0e5;
            margin-right: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            color: #335d7b;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info h2 {
            font-size: 24px;
            font-weight: 600;
            color: #335d7b;
            margin: 0;
        }

        .status-text {
            font-size: 14px;
            color: #668392;
            margin: 5px 0;
        }

        .status-badge {
            display: inline-block;
            background-color: #f3d0d0;
            color: #d84343;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
        }

        .status-badge.active {
            background-color: #d0f3d0;
            color: #43d843;
        }

        .data-diri h3 {
            font-size: 18px;
            font-weight: 600;
            color: #335d7b;
            margin-top: 30px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e0e6ed;
            padding-bottom: 5px;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .label {
            font-size: 16px;
            color: #335d7b;
            font-weight: 500;
        }

        .value {
            font-size: 16px;
            color: #4a4a4a;
            font-weight: 500;
            text-align: right;
        }

        .user-info h2,
        .label,
        .back-link {
            color: #335d7b;
        }

        .status-text {
            color: #668392;
        }

        /* Modal Edit Styles */
        .modal-overlay {
            background: rgba(0, 0, 0, 0.5);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #335d7b;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #335d7b;
            box-shadow: 0 0 0 3px rgba(51, 93, 123, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .radio-input {
            margin: 0;
        }

        /* Upload area styles */
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            border-color: #335d7b;
            background-color: #f0f4f8;
        }

        .upload-area.dragover {
            border-color: #335d7b;
            background-color: #e0e7ff;
        }
    </style>
</head>

<body>

    <x-header />

    <div class="profile-container">
        <div class="profile-card">
            <!-- Tombol Edit di kanan atas -->
            <button onclick="openEditModal()" class="edit-button">
                <i class='bx bx-edit-alt'></i>
                Edit Profil
            </button>

            <a href="{{ route('penghuni.dashboard') }}" class="back-link">
                &#8592; <span>Detail Akun</span>
            </a>

            <div class="header-content">
                <div class="avatar">
                    @if(isset($user->foto_profile) && $user->foto_profile) <!-- ← PERBAIKI: ganti 'profile' menjadi 'foto_profile' -->
                    <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Profile Picture"> <!-- ← PERBAIKI -->
                    @else
                    <span style="font-size: 40px;">
                        {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                    </span>
                    @endif
                </div>
                 <div class="user-info">
                    <h2>{{ $user->nama_lengkap ?? $user->username }}</h2>
                    <p class="text-sm text-gray-500">Peran: {{ $user->peran ?? '-' }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusAktif === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $statusAktif }}
                </span>
                </div>
            </div>

            <div class="data-diri">
                <h3>Data Diri</h3>
                <div class="data-row">
                    <span class="label">Nama Lengkap</span>
                    <span class="value">{{ $user->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="data-row">
                    <span class="label">Jenis Kelamin</span>
                    <span class="value">
                        @if($user->jenis_kelamin == 'L')
                        Laki-Laki
                        @elseif($user->jenis_kelamin == 'P')
                        Perempuan
                        @else
                        -
                        @endif
                    </span>
                </div>
                <div class="data-row">
                    <span class="label">Nomor Telepon</span>
                    <span class="value">{{ $user->no_telepon ?? '-' }}</span>
                </div>
                <div class="data-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $user->email ?? '-' }}</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity modal-overlay" onclick="closeEditModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            Edit Profil
                        </h3>
                        <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <form id="editProfileForm" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Input Foto Profil Baru -->
                        <div class="form-group">
                            <label class="form-label">Foto Profil</label>

                            <!-- Preview foto profil saat ini -->
                            @if($user->foto_profile) <!-- ← PERBAIKI: ganti 'profile' menjadi 'foto_profile' -->
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2 font-medium">Foto saat ini:</p>
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $user->foto_profile) }}"
                                        alt="Foto Profil {{ $user->username }}"
                                        class="w-20 h-20 rounded-full object-cover border border-gray-300">
                                    <div class="text-sm text-gray-500">
                                        <p class="font-medium">{{ $user->username }}</p>
                                        <p>Klik tombol di bawah untuk mengubah foto</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Input file untuk upload foto baru -->
                            <div class="flex items-center justify-center w-full">
                                <label for="foto_profile" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors upload-area">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class='bx bx-cloud-upload text-2xl text-gray-400 mb-2'></i>
                                        <p class="mb-1 text-sm text-gray-500">
                                            <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, JPEG (Max. 2MB)
                                        </p>
                                    </div>
                                    <input id="foto_profile" name="foto_profile" type="file" class="hidden" accept="image/*" />
                                </label>
                            </div>

                            <!-- Preview foto baru yang dipilih -->
                            <div id="newPhotoPreview" class="hidden mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-sm text-green-600 mb-2 font-medium">Foto baru:</p>
                                <img id="previewImage" src="" alt="Preview Foto Baru" class="w-20 h-20 rounded-full object-cover border border-green-300 mx-auto">
                            </div>

                            <p class="text-xs text-gray-500 mt-2">
                                Biarkan kosong jika tidak ingin mengubah foto profil
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap ?? '' }}"
                                class="form-input" placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="jenis_kelamin" value="L"
                                        {{ $user->jenis_kelamin == 'L' ? 'checked' : '' }} class="radio-input">
                                    Laki-Laki
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="jenis_kelamin" value="P"
                                        {{ $user->jenis_kelamin == 'P' ? 'checked' : '' }} class="radio-input">
                                    Perempuan
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="tel" name="no_telepon" value="{{ $user->no_telepon ?? '' }}"
                                class="form-input" placeholder="Masukkan nomor telepon">
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex space-x-4">
                            <button type="submit" class="flex-1 py-3 px-4 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition-colors duration-200">
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeEditModal()" class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                        const otherMenu = document.getElementById(dropdown);
                        if (otherMenu) {
                            otherMenu.classList.add('hidden');
                        }
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

            // Setup upload area drag and drop
            setupUploadArea();
        });

        // Setup upload area dengan drag and drop
        function setupUploadArea() {
            const uploadArea = document.querySelector('.upload-area');
            const fileInput = document.getElementById('foto_profile');

            if (!uploadArea || !fileInput) return;

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            uploadArea.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                uploadArea.classList.add('dragover');
            }

            function unhighlight() {
                uploadArea.classList.remove('dragover');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                handleFileSelect(files[0]);
            }

            // Handle file input change
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    handleFileSelect(this.files[0]);
                }
            });
        }

        // Handle file selection and preview
        function handleFileSelect(file) {
            const previewContainer = document.getElementById('newPhotoPreview');
            const previewImage = document.getElementById('previewImage');

            // Validasi ukuran file
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file && file.size > maxSize) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                document.getElementById('foto_profile').value = '';
                previewContainer.classList.add('hidden');
                return;
            }

            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (file && !validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan format JPG, JPEG, atau PNG.');
                document.getElementById('foto_profile').value = '';
                previewContainer.classList.add('hidden');
                return;
            }

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        // Fungsi untuk membuka modal Edit Profil
        function openEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Pastikan semua dropdown tertutup
            const profileMenu = document.getElementById('profile-menu');
            const contactDropdown = document.getElementById('contact-dropdown');
            if (profileMenu) profileMenu.classList.add('hidden');
            if (contactDropdown) contactDropdown.classList.add('hidden');
        }

        // Fungsi untuk menutup modal Edit Profil
        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Fungsi untuk membuka modal About Us
        function openAboutModal() {
            const modal = document.getElementById('aboutModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Pastikan semua dropdown tertutup
            const profileMenu = document.getElementById('profile-menu');
            const contactDropdown = document.getElementById('contact-dropdown');
            if (profileMenu) profileMenu.classList.add('hidden');
            if (contactDropdown) contactDropdown.classList.add('hidden');
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

            // Pastikan semua dropdown tertutup
            const profileMenu = document.getElementById('profile-menu');
            const contactDropdown = document.getElementById('contact-dropdown');
            if (profileMenu) profileMenu.classList.add('hidden');
            if (contactDropdown) contactDropdown.classList.add('hidden');
        }

        // Fungsi untuk menutup modal Logout
        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closeAboutModal();
                closeLogoutModal();

                // Tutup semua dropdown saat Esc
                const profileMenu = document.getElementById('profile-menu');
                const contactDropdown = document.getElementById('contact-dropdown');
                if (profileMenu) profileMenu.classList.add('hidden');
                if (contactDropdown) contactDropdown.classList.add('hidden');
            }
        });

        // Handle form submission
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            // Validasi tambahan sebelum submit
            const fileInput = document.getElementById('foto_profile');
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (file.size > maxSize) {
                    e.preventDefault();
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    e.preventDefault();
                    alert('Format file tidak didukung. Gunakan format JPG, JPEG, atau PNG.');
                    return;
                }
            }

            // Jika semua validasi passed, form akan di-submit secara normal
        });
    </script>
</body>

</html>