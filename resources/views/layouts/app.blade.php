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

    <script>
        function updateActiveSidebar() {
          const currentUrl = window.location.href;
          $(".ajax-link").each(function() {
            const linkUrl = $(this).data("url");
            if (currentUrl.indexOf(linkUrl) !== -1) {
              // Link aktif: hapus kelas hover dan tetapkan styling aktif
              $(this)
                .removeClass("text-gray-800 hover:text-[#4268F6]")
                .addClass("bg-[#4268F6] text-white");
              $(this).find("svg")
                .removeClass("text-gray-800 group-hover:text-[#4268F6]")
                .addClass("text-white");
              $(this).find("p")
                .removeClass("group-hover:text-[#4268F6]");
            } else {
              // Link non-aktif: terapkan kembali kelas default
              $(this)
                .removeClass("bg-[#4268F6] text-white")
                .addClass("text-gray-800 hover:text-[#4268F6]");
              $(this).find("svg")
                .removeClass("text-white")
                .addClass("text-gray-800 group-hover:text-[#4268F6]");
              $(this).find("p")
                .addClass("group-hover:text-[#4268F6]");
            }
          });
        }
      
        $(document).ready(function() {
          updateActiveSidebar();
      
          $(".ajax-link").on("click", function(e) {
            e.preventDefault();
            const url = $(this).data("url");
            window.history.pushState(null, "", url);
            updateActiveSidebar();
            $("#main-content").html("<p class='text-center py-10'>Loading...</p>");
            $.ajax({
              url: url,
              type: "GET",
              success: function(response) {
                $("#main-content").html($(response).find("#main-content").html());
              },
              error: function() {
                $("#main-content").html("<p class='text-center py-10 text-red-500'>Failed to load content.</p>");
              }
            });
          });
      
          window.onpopstate = function() {
            const url = window.location.href;
            updateActiveSidebar();
            $("#main-content").html("<p class='text-center py-10'>Loading...</p>");
            $.ajax({
              url: url,
              type: "GET",
              success: function(response) {
                $("#main-content").html($(response).find("#main-content").html());
              }
            });
          };
        });
      </script>
      
      


</body>

</html>
