// Global variables (dapat diupdate di initAccountManagement)
let sortBy = 'id';
let sortOrder = 'asc';
let perPage = 10;

// Fungsi untuk membaca parameter dari URL dan menginisialisasi variabel global
function initAccountManagement() {
    let queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || 'id';
    sortOrder = queryParams.get('sortOrder') || 'asc';
    perPage = queryParams.get('perPage') || 10;
}

// Panggil inisialisasi saat document ready
$(document).ready(function () {
    initAccountManagement();
});

// Dengar event inisialisasi ulang
$(document).on("accountManagement:init", function() {
    initAccountManagement();
});

// Fungsi utama untuk update data pengguna via AJAX
function fetchUsers(page = 1) {
    $.ajax({
        url: window.location.pathname, // URL halaman saat ini
        type: 'GET',
        dataType: 'json',
        data: {
            search: $('#search').val(),
            sortBy: sortBy,
            sortOrder: sortOrder,
            perPage: perPage,
            page: page
        },
        success: function (response) {
            $('#userTableContainer').html(response.html);
            // Perbarui URL tanpa reload halaman
            let newQuery = new URLSearchParams({
                search: $('#search').val(),
                sortBy: sortBy,
                sortOrder: sortOrder,
                perPage: perPage,
                page: page
            }).toString();
            window.history.pushState(null, '', '?' + newQuery);
        },
        error: function () {
            alert('Error fetching user data.');
        }
    });
}

// Gunakan delegated event binding agar handler tetap aktif walaupun konten di-replace
$(document).on('submit', '#searchForm', function (e) {
    e.preventDefault();
    fetchUsers(1);
});

$(document).on('click', '#toggleSortOrder', function () {
    sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
    $(this).toggleClass('bg-gray-200');
    fetchUsers(1);
});

$(document).on('click', '#sortByButton', function (e) {
    e.stopPropagation();
    $('#sortByPopup').fadeToggle(200);
});

$(document).on('click', function (e) {
    if (!$(e.target).closest('#sortByButton, #sortByPopup').length) {
        $('#sortByPopup').fadeOut(200);
    }
});

$(document).on('click', '.sort-option', function () {
    sortBy = $(this).data('sortby');
    fetchUsers(1);
    $('#sortByPopup').fadeOut(200);
});

$(document).on('change', '#perPage', function () {
    perPage = $(this).val();
    fetchUsers(1);
});

// Delegasi klik untuk pagination link (pastikan selector sesuai dengan markup Tailwind)
$(document).on('click', '#userTableContainer nav a', function (e) {
    e.preventDefault();
    let href = $(this).attr('href');
    if (href) {
        let url = new URL(href, window.location.origin);
        let page = url.searchParams.get('page') || 1;
        fetchUsers(page);
    }
    return false;
});

// Delegated event untuk checkbox
$(document).on('change', '#checkbox-all', function () {
    $('.checkbox-row').prop('checked', $(this).prop('checked'));
});
$(document).on('change', '.checkbox-row', function () {
    $('#checkbox-all').prop('checked', $('.checkbox-row').length === $('.checkbox-row:checked').length);
});
