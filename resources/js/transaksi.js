const baseUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;

// Global variables for sorting, pagination, etc.
let sortBy = 'id';
let sortOrder = 'asc';
let perPage = 10;

// Initialize parameters from URL query
function initTransaksis() {
    const queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || sortBy;
    sortOrder = queryParams.get('sortOrder') || sortOrder;
    perPage = queryParams.get('perPage') || perPage;

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

// Initial setup
initTransaksis();

// Event listener to reinitialize when triggered (for AJAX content load)
$(document).on("transaksis:init", function () {
    initTransaksis();
    fetchTransaksis(1);  // Fetch users after initialization
});

// Update browser URL without reloading
function updateUrl(page) {
    const query = new URLSearchParams({
        search: $('#search').val(),
        sortBy,
        sortOrder,
        perPage,
        page
    }).toString();
    window.history.replaceState(null, '', baseUrl + '?' + query);
}

// Fetch user data based on current parameters
const url = $('#searchFormTransaksi').attr('action');
function fetchTransaksis(page = 1) {
    const uniqueParam = new Date().getTime();  // Menambahkan timestamp untuk mencegah cache
    $.ajax({
        url: url,
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
            $('#transaksiTableContainer').html(response.html);
            updateUrl(page);
        },
        error: function () {
            alert('Error fetching Transaksi data.');
        }
    });
}


// Search
$(document).on('submit', '#searchFormTransaksi', function (e) {
    e.preventDefault();
    fetchTransaksis(1);
});

// Toggle sorting order and fetch users
$(document).on('click', '#toggleSortOrder', function () {
    sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
    $(this).toggleClass('bg-gray-200');
    fetchTransaksis(1);
});

// Select the sorting option and fetch users
$(document).on('click', '.sort-option', function () {
    sortBy = $(this).data('sortby');
    fetchTransaksis(1);
    $('#sortByPopup').fadeOut(200);
});

// Toggle sort popup
$(document).on('click', '#sortByButton', function (e) {
    e.stopPropagation();
    $('#sortByPopup').toggleClass('hidden');
});

$(document).on('click', function (e) {
    if (!$(e.target).closest('#sortByButton, #sortByPopup').length) {
        $('#sortByPopup').toggleClass('hidden');
    }
});

// Change entries per page
$(document).on('change', '#perPage', function () {
    perPage = $(this).val();
    fetchTransaksis(1);
});

// Handle pagination link clicks
$(document).on('click', '#transaksiTableContainer nav a', function (e) {
    e.preventDefault();
    const href = $(this).attr('href');
    if (href) {
        const url = new URL(href, window.location.origin);
        const page = url.searchParams.get('page') || 1;
        fetchTransaksis(page);
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
$(document).on('click', '.delete-transaksi', function (e) {
    e.preventDefault();

    let idTransaksi = $(this).data('id');  // Ambil ID Transaksi
    let row = $(this).closest('tr');  // Ambil baris tabel

    // Tampilkan SweetAlert untuk konfirmasi hapus
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data Transaksi akan dihapus secara permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/transaksis/single/' + idTransaksi,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        row.fadeOut(300, function () {
                            $(this).remove();
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Transaksi berhasil dihapus.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menghapus Transaksi.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan. Pastikan transaksi masih ada.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});


$(document).on('submit', '#deleteFormTransaksi', function (e) {
    e.preventDefault(); // Cegah submit form secara tradisional

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Transaksi yang dipilih akan dihapus secara permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(), // Mengirim data form (termasuk _token dan _method)
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                        // Refresh tabel transaksi via AJAX (misalnya, panggil fungsi fetchTransaksis)
                        fetchTransaksis(1);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menghapus Transaksi.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});
