<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif

</head>

<body class="font-inter antialiased dark:bg-black dark:text-white/50">
    {{-- <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3 bg-blue-300">

    </header> --}}

    <main class="w-full h-screen overflow-hidden">
        <div class="flex flex-row h-full">
            <div class="w-[40%] bg-[#4268F6] p-5 flex flex-col justify-center items-center">
                <img src="{{ url('pharamacylogo.jpg') }}" alt="logo" class="h-[20rem] rounded-full mb-2">
                <p class="text-white font-outfit text-[3rem] uppercase tracking-wider">Selamat Datang</p>
                <p class="text-white font-outfit text-lg">Kesehatan Anda, kebahagiaan kami.</p>
            </div>

            {{-- ------------------------------------------------------------------ --}}

            <div class="w-[60%] bg-white flex flex-col p-10 justify-center items-center">
                <div class="flex flex-row items-center mb-2 justify-center">
                    <svg class="w-16 h-16 text-[#4268F6]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-[3rem] font-outfit">Admin Core</p>
                </div>
                <p class="text-xl text-gray-500 font-outfit ml-3 mb-24">Login atau daftar akun apotek anda</p>
                <div class="">
                    @if (Route::has('login'))
                        <nav class="flex flex-col font-outfit justify-center items-center">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="bg-[#4268F6] w-[20rem] py-2 text-white text-2xl text-center rounded-xl mb-3">
                                    Log in
                                </a>

                                <p class="text-base text-gray-500 mb-3 text-center">atau</p>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="bg-[#4268F6] w-[20rem] py-2 text-white text-2xl text-center rounded-xl mb-3">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </main>
    {{-- <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </footer> --}}
</body>

</html>
