$(document).ready(function () {

    /**
     * Fungsi untuk memperbarui gaya aktif pada elemen sidebar
     * Menandai elemen yang sedang aktif berdasarkan URL saat ini.
     */
    function updateActiveSidebar() {
        const currentUrl = window.location.href; // Ambil URL halaman saat ini

        // Iterasi pada setiap elemen dengan kelas .ajax-link dan .dropdown-parent
        $(".ajax-link, .dropdown-parent").each(function () {
            const linkUrl = $(this).data("url"); // Ambil URL dari data-url elemen

            // Cek apakah URL elemen ini ada dalam URL halaman saat ini
            if (currentUrl.indexOf(linkUrl) !== -1) {
                // Jika cocok, beri gaya aktif pada elemen
                $(this)
                    .removeClass("text-gray-800 hover:text-[#4268F6]")
                    .addClass("bg-[#4268F6] text-white active");

                $(this).find("svg")
                    .removeClass("text-gray-800 group-hover:text-[#4268F6]")
                    .addClass("text-white");

                $(this).find("p")
                    .removeClass("group-hover:text-[#4268F6]");

                // Jika elemen adalah dropdown parent, pastikan dropdown ditampilkan dan panah berotasi
                if ($(this).hasClass("dropdown-parent")) {
                    $(this).next(".dropdown-menu").slideDown();
                    $(this).find(".arrow-icon").addClass("rotate-90");
                }
            } else {
                // Jika tidak cocok, kembalikan gaya default
                $(this)
                    .removeClass("bg-[#4268F6] text-white active")
                    .addClass("text-gray-800 hover:text-[#4268F6]");

                $(this).find("svg")
                    .removeClass("text-white")
                    .addClass("text-gray-800 group-hover:text-[#4268F6]");

                $(this).find("p")
                    .addClass("group-hover:text-[#4268F6]");
            }
        });
    }

    // Inisialisasi sidebar saat halaman pertama kali dimuat
    updateActiveSidebar();

    /**
     * Event handler untuk link biasa (.ajax-link) yang menggunakan AJAX untuk navigasi.
     * Mengupdate URL dan memuat konten halaman baru ke dalam #main-content.
     */
    $(document).on("click", ".ajax-link", function (e) {
        e.preventDefault(); // Mencegah perilaku default link

        const url = $(this).data("url"); // Ambil URL tujuan dari data-url elemen

        // Cek jika URL yang diminta sama dengan URL yang sedang aktif
        if (window.location.href !== url) {
            window.history.pushState(null, "", url); // Ubah URL di address bar
            updateActiveSidebar(); // Update gaya aktif di sidebar
            $("#main-content").html("<p class='text-center py-10'>Loading...</p>"); // Tampilkan loading indicator

            // Lakukan request AJAX untuk memuat konten baru
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    // Jika respons berupa JSON (partial), periksa properti html
                    if (response.html) {
                        $("#main-content").html(response.html); // Ganti konten dengan HTML baru
                    } else {
                        // Jika respons tidak berisi properti html, ambil konten dari elemen #main-content
                        $("#main-content").html($(response).find("#main-content").html());
                    }

                    // Jika halaman yang dimuat adalah Halaman Index Obat, trigger inisialisasi ulang
                    if ($("#obatTableContainer").length > 0) {
                        $(document).trigger("obats:init");
                    }

                    // Jika halaman yang dimuat adalah Halaman Index Obat, trigger inisialisasi ulang
                    if ($("#pembelian_obatTableContainer").length > 0) {
                        $(document).trigger("pembelian_obats:init");
                    }

                    // Jika halaman yang dimuat adalah Account Management, trigger inisialisasi ulang
                    if ($("#userTableContainer").length > 0) {
                        $(document).trigger("accountManagement:init");
                    }
                },
                error: function () {
                    // Jika terjadi error, tampilkan pesan error
                    $("#main-content").html("<p class='text-center py-10 text-red-500'>Failed to load content.</p>");
                }
            });
        }
    });

    /**
     * Event handler untuk klik pada dropdown parent, selain ikon panah
     * Menghindari klik pada ikon panah untuk menghindari toggling dropdown
     */
    $(document).on("click", ".dropdown-parent", function (e) {
        if (!$(e.target).closest(".arrow-icon").length) {
            e.preventDefault(); // Mencegah navigasi
            const url = $(this).data("url"); // Ambil URL dari data-url elemen

            window.history.pushState(null, "", url); // Ubah URL di address bar
            updateActiveSidebar(); // Update gaya sidebar
            $("#main-content").html("<p class='text-center py-10'>Loading...</p>"); // Tampilkan loading indicator

            // Lakukan request AJAX untuk memuat konten baru
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    $("#main-content").html($(response).find("#main-content").html()); // Ganti konten
                },
                error: function () {
                    // Tampilkan pesan error jika gagal
                    $("#main-content").html("<p class='text-center py-10 text-red-500'>Failed to load content.</p>");
                }
            });
        }
    });

    /**
     * Event handler untuk klik pada ikon panah di dropdown parent
     * Berfungsi untuk meng-toggle dropdown (menampilkan atau menyembunyikan dropdown)
     */
    $(document).on("click", ".dropdown-parent .arrow-icon", function (e) {
        e.stopPropagation(); // Menghentikan event agar tidak memicu event pada parent
        e.preventDefault();
        const $arrow = $(this);
        const $dropdown = $arrow.closest(".dropdown-parent").next(".dropdown-menu");

        // Toggle rotasi ikon panah dan toggle visibility dropdown
        $arrow.toggleClass("rotate-90");
        $dropdown.stop(true, true).slideToggle();
    });

    /**
     * Tangani navigasi dengan tombol back/forward pada browser
     * Mengubah konten sesuai dengan URL yang dipilih saat menggunakan tombol back/forward
     */
    window.onpopstate = function () {
        const url = window.location.href; // Ambil URL saat ini
        updateActiveSidebar(); // Update sidebar aktif
        $("#main-content").html("<p class='text-center py-10'>Loading...</p>"); // Tampilkan loading

        // Lakukan request AJAX untuk memuat konten halaman
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#main-content").html($(response).find("#main-content").html()); // Ganti konten
            }
        });
    };

});
