<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sikosan</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<!-- Background -->

<body class="bg-gray-100 h-screen overflow-hidden">
    <div class="absolute top-0 right-0 h-screen w-1/2 bg-[#006A7A] "></div>

    <!-- Header
        <header class="absolute bg-white top-0 left-0 w-full p-2 z-10 shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold ml-8 text-[#006A7A]">Sikosan</h1>
                <nav class="flex gap-6">
                    <a href="#" class="font-semibold mr-8 text-[#006A7A]">contact</a>
                    <a href="#" class="font-semibold mr-8 text-[#006A7A]">about us</a>
                </nav>
            </div>
        </header> -->

    <!-- posisi kartu -->
    <main class="relative z-10 flex min-h-screen items-center justify-center py-12">

        <!-- ukuran kartu -->
        <div class="w-full max-w-md p-6 mt-2 bg-white rounded-2xl shadow-2xl">

            <!-- judul kartu -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-[#006A7A]">Sikosan</h2>
                <p class="text-gray-500">create an account</p>
            </div>

            <form class="space-y-4" action="/register/create" method="POST">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
                    <input id="username" name="username" type="text" required value="{{ old('username') }}"
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#006A7A] focus:border-[#006A7A]">
                    @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#006A7A] focus:border-[#006A7A]">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#006A7A] focus:border-[#006A7A]">
                    @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#006A7A] focus:border-[#006A7A]">
                    @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-8 pt-2">
                    <div class="flex items-center">
                        <input id="role-pemilik" name="peran" type="radio" value="pemilik" checked class="h-4 w-4 text-[#006A7A] focus:ring-[#005663] border-gray-300">
                        <label for="role-pemilik" class="ml-2 block text-sm text-gray-900">Pemilik</label>
                    </div>
                    <div class="flex items-center">
                        <input id="role-penghuni" name="peran" type="radio" value="penghuni" class="h-4 w-4 text-[#006A7A] focus:ring-[#005663] border-gray-300">
                        <label for="role-penghuni" class="ml-2 block text-sm text-gray-900">Penghuni</label>
                    </div>
                </div>

                <div class="flex items-center pt-2">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-[#006A7A] focus:ring-[#005663]">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">I agree to the Terms of Service</label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-[#006A7A] hover:bg-[#005663] transition-colors duration-200">
                        register
                    </button>
                </div>
            </form>

            <!--  redirect Login -->
            <div class="text-center">
                <p class="text-sm text-gray-500">
                    already have an account?
                    <a href="/login" class="font-bold text-[#006A7A] underline hover:text-[#005663]">
                        LOGIN HERE
                    </a>
                </p>
            </div>
        </div>
    </main>

</body>

</html>