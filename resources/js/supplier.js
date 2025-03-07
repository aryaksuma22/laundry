// Global Variables
let sortBy = 'id';
let sortOrder = 'asc';
let perPage = 10;

// Initialize the account management parameters from the URL
function initSuppliers() {
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
    initSuppliers();
});

// Event listener to reinitialize when triggered (for AJAX content load)
$(document).on("suppliers:init", function () {
    initSuppliers();
    fetchSuppliers(1);  // Fetch users after initialization
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
function fetchSuppliers(page = 1) {
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
            $('#supplierTableContainer').html(response.html);
            updateUrl(page);
        },
        error: function () {
            alert('Error fetching suppliers data.');
        }
    });
}

// Form submission to fetch users based on search input
$(document).on('submit', '#searchForm', function (e) {
    e.preventDefault();
    fetchSuppliers(1);
});

// Toggle sorting order and fetch users
$(document).on('click', '#toggleSortOrder', function () {
    sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
    $(this).toggleClass('bg-gray-200');
    fetchSuppliers(1);
});

// Handle sort-by button dropdown visibility
$(document).on('click', '#sortByButton', function (e) {
    e.stopPropagation();
    $('#sortByPopup').toggleClass('hidden');
});

// Close sort-by dropdown if clicked outside
$(document).on('click', function (e) {
    if (!$(e.target).closest('#sortByButton, #sortByPopup').length) {
        $('#sortByPopup').toggleClass('hidden');
    }
});

// Select the sorting option and fetch users
$(document).on('click', '.sort-option', function () {
    sortBy = $(this).data('sortby');
    fetchSuppliers(1);
    $('#sortByPopup').fadeOut(200);
});

// Change the number of items per page and fetch users
$(document).on('change', '#perPage', function () {
    perPage = $(this).val();
    fetchSuppliers(1);
});

// Handle pagination link clicks
$(document).on('click', '#supplierTableContainer nav a', function (e) {
    e.preventDefault();
    const href = $(this).attr('href');
    if (href) {
        const url = new URL(href, window.location.origin);
        const page = url.searchParams.get('page') || 1;
        fetchSuppliers(page);
    }
    return false;
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
$(document).on('click', '.delete-supplier', function (e) {
    e.preventDefault();

    let idSupplier = $(this).data('id');
    let row = $(this).closest('tr');

    if (!confirm('Apakah Anda yakin ingin menghapus data supplier ini?')) {
        return;
    }

    $.ajax({
        url: '/suppliers/single/' + idSupplier,
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                row.fadeOut(300, function () { $(this).remove(); });
                alert('Supplier berhasil dihapus.');
            } else {
                alert('Gagal menghapus Supplier.');
            }
        },
        error: function (xhr) {
            alert('Terjadi kesalahan. Pastikan Supplier masih ada.');
        }
    });
});
