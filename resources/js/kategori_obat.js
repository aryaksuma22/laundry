// Global Variables
let sortBy = 'id';
let sortOrder = 'asc';
let perPage = 10;

// Initialize the account management parameters from the URL
function initKategoriObats() {
    const queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || 'id';
    sortOrder = queryParams.get('sortOrder') || 'asc';
    perPage = queryParams.get('perPage') || 10;

    // Jika ada hidden inputs, kita bisa set nilainya (opsional)
    if ($('#sort-by').length) {
        $('#sort-by').val(sortBy);
    }
    if ($('#sort-order').length) {
        $('#sort-order').val(sortOrder);
    }
    if ($('#perPage').length) {
        $('#perPage').val(perPage);
    }
}

// Initialize the page on document ready
$(document).ready(function () {
    initKategoriObats();
});

// Event listener to reinitialize when triggered (for AJAX content load)
$(document).on("satuan_obats:init", function () {
    initKategoriObats();
    fetchKategoriObats(1);  // Fetch users after initialization
});

// Update the URL with the current parameters
function updateUrl(page) {
    const newQuery = new URLSearchParams({
        search: $('#search').val(),
        sortBy: sortBy,
        sortOrder: sortOrder,
        perPage: perPage,
        page: page
    }).toString();
    window.history.pushState(null, '', '?' + newQuery);
}

// Fetch user data based on current parameters
function fetchKategoriObats(page = 1) {
    const uniqueParam = new Date().getTime();  // Menambahkan timestamp untuk mencegah cache
    $.ajax({
        url: window.location.pathname + '?_=' + uniqueParam,
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
            $('#kategori_obatTableContainer').html(response.html);
            updateUrl(page);
        },
        error: function () {
            alert('Error fetching kategori_obats data.');
        }
    });
}

// Form submission to fetch users based on search input
$(document).on('submit', '#searchForm', function (e) {
    e.preventDefault();
    fetchKategoriObats(1);
});

// Handle "select all" checkbox click
$(document).on('change', '#checkbox-all', function () {
    $('.checkbox-row').prop('checked', $(this).prop('checked'));
});

// Update the "select all" checkbox state based on individual checkbox status
$(document).on('change', '.checkbox-row', function () {
    const allChecked = $('.checkbox-row').length === $('.checkbox-row:checked').length;
    $('#checkbox-all').prop('checked', allChecked);
});

// Single Delete
$(document).on('click', '.delete-kategori_obat', function (e) {
    e.preventDefault();

    let idKategoriObat = $(this).data('id');
    let row = $(this).closest('tr');

    if (!confirm('Apakah Anda yakin ingin menghapus kategori obat ini?')) {
        return;
    }

    $.ajax({
        url: '/kategori_obats/single/' + idKategoriObat,
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                row.fadeOut(300, function () { $(this).remove(); });
                alert('Kategori Obat berhasil dihapus.');
            } else {
                alert('Gagal menghapus Kategori Obat.');
            }
        },
        error: function (xhr) {
            alert('Terjadi kesalahan. Pastikan Kategori Obat masih ada.');
        }
    });
});
