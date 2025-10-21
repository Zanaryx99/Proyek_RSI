<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sikosan</title>
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

<body class="bg-gray-100">
    <div class="absolute top-0 right-0 h-full w-1/2 bg-[#006A7A]"></div>

    <!-- Header -->
    <header class="absolute bg-white top-0 left-0 w-full p-2 z-10 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold ml-8 text-[#006A7A]">Sikosan</h1>
            <nav class="flex gap-6">
                <a href="#" class="font-semibold mr-8 text-[#006A7A]">contact</a>
                <a href="#" class="font-semibold mr-8 text-[#006A7A]">about us</a>
            </nav>
        </div>
    </header>

    <!-- posisi kartu -->
    <main class="relative z-10 flex min-h-screen items-center justify-center">

        <!-- ukuran kartu -->
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-2xl">

            <!-- judul kartu -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-[#006A7A]">Sikosan</h2>
                <p class="text-gray-500">create an account</p>
            </div>

            <form class="space-y-6" action="/login" method="POST">
                @csrf

                <div>
                    <label for="login" class="block text-sm font-medium text-gray-600">Email/Username</label>
                    <div class="mt-1">
                        <input id="login" name="login" type="text" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#006A7A] focus:border-[#006A7A]">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#006A7A] focus:border-[#006A7A]">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-[#006A7A] hover:bg-[#005663] transition-colors duration-200">
                        login
                    </button>
                </div>
            </form>

            <!--  redirect register -->
            <div class="text-center">
                <p class="text-sm text-gray-500">
                    don't have an account?
                    <a href="/register" class="font-bold text-[#006A7A] underline hover:text-[#005663]">
                        REGISTER HERE
                    </a>
                </p>
            </div>
        </div>
    </main>

</body>

</html>