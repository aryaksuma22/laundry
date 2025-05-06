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
            <div class="w-[30%] bg-[#4769e4] p-5 flex flex-col justify-center items-center shrink-0">
                <img src="{{ url('laundry.jpg') }}" alt="logo" class="h-auto max-h-[20rem] w-auto rounded-2xl mb-5">
                <p class="text-white font-outfit text-2xl sm:text-3xl text-center uppercase tracking-wider">Laundry</p>
                <p class="text-white font-outfit text-base sm:text-lg text-center">Cucian numpuk? Serahkan pada kami</p>
            </div>

            <div class="w-[70%] flex-1 flex flex-col overflow-y-auto">
                <div class="w-full p-6 md:p-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
