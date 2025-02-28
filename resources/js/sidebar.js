// resources/js/sidebar.js

$(document).ready(function () {



    // Fungsi update active style untuk elemen .ajax-link dan .dropdown-parent
    function updateActiveSidebar() {
        const currentUrl = window.location.href;
        $(".ajax-link, .dropdown-parent").each(function () {
            const linkUrl = $(this).data("url");
            if (currentUrl.indexOf(linkUrl) !== -1) {
                $(this)
                    .removeClass("text-gray-800 hover:text-[#4268F6]")
                    .addClass("bg-[#4268F6] text-white active");
                $(this).find("svg")
                    .removeClass("text-gray-800 group-hover:text-[#4268F6]")
                    .addClass("text-white");
                $(this).find("p")
                    .removeClass("group-hover:text-[#4268F6]");
                // Jika ini dropdown parent, pastikan dropdown tampil dan panah berotasi
                if ($(this).hasClass("dropdown-parent")) {
                    $(this).next(".dropdown-menu").slideDown();
                    $(this).find(".arrow-icon").addClass("rotate-90");
                }
            } else {
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

    // Inisialisasi tampilan sidebar saat halaman siap
    updateActiveSidebar();

    // Event handler untuk link biasa (untuk elemen dengan class .ajax-link)
    $(document).on("click", ".ajax-link", function (e) {
        e.preventDefault();
        const url = $(this).data("url");
        window.history.pushState(null, "", url);
        updateActiveSidebar();
        $("#main-content").html("<p class='text-center py-10'>Loading...</p>");
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                // Jika respons berupa JSON (partial), cek properti html
                if (response.html) {
                    $("#main-content").html(response.html);
                } else {
                    $("#main-content").html($(response).find("#main-content").html());
                }
                // Setelah konten baru dimasukkan, jika halaman Account Management, trigger inisialisasi ulang
                if ($("#userTableContainer").length > 0) {
                    $(document).trigger("accountManagement:init");
                }
            },
            error: function() {
                $("#main-content").html("<p class='text-center py-10 text-red-500'>Failed to load content.</p>");
            }
        });
        
        
    });

    // Event handler untuk klik pada parent (selain ikon panah)
    $(document).on("click", ".dropdown-parent", function (e) {
        // Pastikan klik yang tidak berasal dari ikon panah (arrow-icon)
        if (!$(e.target).closest(".arrow-icon").length) {
            e.preventDefault();
            const url = $(this).data("url");
            window.history.pushState(null, "", url);
            updateActiveSidebar();
            $("#main-content").html("<p class='text-center py-10'>Loading...</p>");
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    $("#main-content").html($(response).find("#main-content").html());
                },
                error: function () {
                    $("#main-content").html("<p class='text-center py-10 text-red-500'>Failed to load content.</p>");
                }
            });
        }
    });

    // Event handler untuk klik pada ikon panah di dropdown parent
    $(document).on("click", ".dropdown-parent .arrow-icon", function (e) {
        e.stopPropagation(); // Menghentikan event agar tidak memicu event pada parent
        e.preventDefault();
        const $arrow = $(this);
        const $dropdown = $arrow.closest(".dropdown-parent").next(".dropdown-menu");
        $arrow.toggleClass("rotate-90");
        $dropdown.stop(true, true).slideToggle();
    });

    // Tangani navigasi dengan tombol back/forward browser
    window.onpopstate = function () {
        const url = window.location.href;
        updateActiveSidebar();
        $("#main-content").html("<p class='text-center py-10'>Loading...</p>");
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#main-content").html($(response).find("#main-content").html());
            }
        });
    };




});
