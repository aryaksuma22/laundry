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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
    <!-- Link ke jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <!-- Navigasi -->
    <div>
        @include('layouts.navigation')
    </div>

    <!-- Konten utama -->
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-1/6 border-r">
            @include('layouts.sidebar')
        </div>

        <!-- Konten utama -->
        <div class="w-5/6 flex flex-col">
            <!-- Konten Halaman -->
            <main class="flex-1" id="main-content">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Stack untuk script khusus -->
    @stack('scripts')

    @vite(['resources/js/app.js', 'resources/js/account-management.js', 'resources/js/obat.js', 'resources/js/pembelian_obat.js', 'resources/js/penjualan_obat.js','resources/js/supplier.js','resources/js/satuan_obat.js','resources/js/kategori_obat.js'])

</body>

</html>
