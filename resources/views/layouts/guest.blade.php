<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="w-full h-screen overflow-hidden">
        <div class="flex flex-row h-full">
            <div class="w-[40%] bg-[#4268F6] p-5 flex flex-col justify-center items-center">
                <img src="{{ url('pharamacylogos.jpg') }}" alt="logo" class="h-[20rem] rounded-2xl mb-5">
                <p class="text-white font-outfit text-[3rem] uppercase tracking-wider">Selamat Datang</p>
                <p class="text-white font-outfit text-lg">Kesehatan Anda, kebahagiaan kami.</p>
            </div>
            <div class="w-[60%] flex flex-col p-10 justify-center items-center">
                <div class="w-1/3">

                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
