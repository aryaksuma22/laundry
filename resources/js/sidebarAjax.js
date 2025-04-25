

/**
 * Fungsi untuk memperbarui gaya aktif pada elemen sidebar
 * Menandai elemen yang sedang aktif berdasarkan URL saat ini.
 */
function updateActiveSidebar() {
    const currentUrl = window.location.href;

    $(".ajax-link, .dropdown-parent").each(function () {
        const linkUrl = $(this).data("url");

        if (currentUrl.indexOf(linkUrl) !== -1) {
            $(this)
                .removeClass("text-gray-800 hover:text-[#4268F6]")
                .addClass("bg-[#4268F6] text-white active");

            $(this).find("#sidebarSVG")
                .removeClass("text-gray-800 group-hover:text-[#4268F6]")
                .addClass("text-white");

            $(this).find("p")
                .removeClass("group-hover:text-[#4268F6]");

            if ($(this).hasClass("dropdown-parent")) {
                $(this).next(".dropdown-menu").slideDown();
                $(this).find(".arrow-icon").addClass("rotate-90");
            }
        } else {
            $(this)
                .removeClass("bg-[#4268F6] text-white active")
                .addClass("text-gray-800 hover:text-[#4268F6]");

            $(this).find("#sidebarSVG")
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
    e.preventDefault();

    const url = $(this).data("url");

    if (window.location.href !== url) {
        window.history.pushState(null, "", url);
        updateActiveSidebar();
        $("#main-content").html(`                <div id="loadingAnimation" class="flex flex-row gap-2 w-full min-h-screen items-center justify-center">
                    <div class="w-6 h-6 rounded-full bg-[#4268F6] circle1"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3d60e1] circle2"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3758d0] circle3"></div>
                </div>`);

        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                if (response.html) {
                    $("#main-content").html(response.html);
                } else {
                    $("#main-content").html($(response).find("#main-content").html());
                }

                // Ensure proper triggering for "pembelian_obat" page
                if ($("#obatTableContainer").length > 0) {
                    $(document).trigger("obats:init");
                }

                if (typeof initDashboardCharts === 'function' && $("#ordersChart").length) {
                    initDashboardCharts();
                }



                if ($("#pembelian_obatTableContainer").length > 0) {
                    $(document).trigger("pembelian_obats:init");
                }

                if ($("#penjualan_obatTableContainer").length > 0) {
                    $(document).trigger("penjualan_obats:init");
                }

                if ($("#supplierTableContainer").length > 0) {
                    $(document).trigger("suppliers:init");
                }

                if ($("#userTableContainer").length > 0) {
                    $(document).trigger("accountManagement:init");
                }

                if ($("#satuan_obatTableContainer").length > 0) {
                    $(document).trigger("satuan_obats:init");
                }

                if ($("#kategori_obatTableContainer").length > 0) {
                    $(document).trigger("kategori_obats:init");
                }
            },
            error: function () {
                $("#main-content").html(`                <div id="loadingAnimation" class="flex flex-row gap-2 w-full min-h-screen items-center justify-center">
                    <div class="w-6 h-6 rounded-full bg-[#4268F6] circle1"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3d60e1] circle2"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3758d0] circle3"></div>
                </div>`);
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
        e.preventDefault();
        const url = $(this).data("url");

        window.history.pushState(null, "", url);
        updateActiveSidebar();
        $("#main-content").html(`                <div id="loadingAnimation" class="flex flex-row gap-2 w-full min-h-screen items-center justify-center">
                    <div class="w-6 h-6 rounded-full bg-[#4268F6] circle1"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3d60e1] circle2"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3758d0] circle3"></div>
                </div>`);

        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#main-content").html($(response).find("#main-content").html());
            },
            error: function () {
                $("#main-content").html(`                <div id="loadingAnimation" class="flex flex-row gap-2 w-full min-h-screen items-center justify-center">
                    <div class="w-6 h-6 rounded-full bg-[#4268F6] circle1"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3d60e1] circle2"></div>
                    <div class="w-6 h-6 rounded-full bg-[#3758d0] circle3"></div>
                </div>`);
            }
        });
    }
});

/**
 * Event handler untuk klik pada ikon panah di dropdown parent
 * Berfungsi untuk meng-toggle dropdown (menampilkan atau menyembunyikan dropdown)
 */
$(document).on("click", ".dropdown-parent .arrow-icon", function (e) {
    e.stopPropagation();
    e.preventDefault();
    const $arrow = $(this);
    const $dropdown = $arrow.closest(".dropdown-parent").next(".dropdown-menu");

    $arrow.toggleClass("rotate-90");
    $dropdown.stop(true, true).slideToggle();
});

/**
 * Tangani navigasi dengan tombol back/forward pada browser
 * Mengubah konten sesuai dengan URL yang dipilih saat menggunakan tombol back/forward
 */
window.onpopstate = function () {
    const url = window.location.href;
    updateActiveSidebar();
    $("#main-content").html(`                <div id="loadingAnimation" class="flex flex-row gap-2 w-full min-h-screen items-center justify-center">
                    <div class="w-6 h-6 rounded-full bg-[#4266F6] circle1"></div>
                    <div class="w-6 h-6 rounded-full bg-[#4266F6] circle2"></div>
                    <div class="w-6 h-6 rounded-full bg-[#4268F6] circle3"></div>
                </div>`);

    $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
            $("#main-content").html($(response).find("#main-content").html());
            if (typeof initDashboardCharts === 'function' && $("#ordersChart").length) {
                initDashboardCharts();
            }
        }
    });
};
