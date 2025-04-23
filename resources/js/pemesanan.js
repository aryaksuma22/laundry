$(document).ready(function() {
    // Base URL for AJAX requests (taken from search form action)
    const baseUrl = $('#searchForm').attr('action');

    // Global variables for sorting, pagination, etc.
    let sortBy = 'nama_pelanggan';
    let sortOrder = 'asc';
    let perPage = 10;

    // Initialize parameters from URL query
    function initPemesanans() {
        const queryParams = new URLSearchParams(window.location.search);
        sortBy = queryParams.get('sortBy') || sortBy;
        sortOrder = queryParams.get('sortOrder') || sortOrder;
        perPage = queryParams.get('perPage') || perPage;

        // Set hidden inputs and selects accordingly
        $('#sort-by').val(sortBy);
        $('#sort-order').val(sortOrder);
        $('#perPage').val(perPage);
    }

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

    // Fetch data via AJAX
    function fetchPemesanans(page = 1) {
        $.ajax({
            url: baseUrl,
            type: 'GET',
            dataType: 'json',
            data: { search: $('#search').val(), sortBy, sortOrder, perPage, page },
            success(response) {
                $('#pemesananTableContainer').html(response.html);
                updateUrl(page);
            },
            error() {
                Swal.fire('Error', 'Gagal memuat data pemesanan.', 'error');
            }
        });
    }

    // Initial setup
    initPemesanans();

    // Search
    $(document).on('submit', '#searchForm', function(e) {
        e.preventDefault();
        fetchPemesanans(1);
    });

    // Toggle sort order
    $(document).on('click', '#toggleSortOrder', function() {
        sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
        $('#sort-order').val(sortOrder);
        fetchPemesanans(1);
    });

    // Sort by selection
    $(document).on('click', '.sort-option', function() {
        sortBy = $(this).data('sortby');
        $('#sort-by').val(sortBy);
        $('#sortByPopup').addClass('hidden');
        fetchPemesanans(1);
    });

    // Toggle sort popup
    $(document).on('click', '#sortByButton', function(e) {
        e.stopPropagation();
        $('#sortByPopup').toggleClass('hidden');
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#sortByButton, #sortByPopup').length) {
            $('#sortByPopup').addClass('hidden');
        }
    });

    // Change entries per page
    $(document).on('change', '#perPage', function() {
        perPage = $(this).val();
        fetchPemesanans(1);
    });

    // Pagination links
    $(document).on('click', '#pemesananTableContainer nav a', function(e) {
        e.preventDefault();
        const pageParam = new URL($(this).attr('href'), window.location.origin).searchParams.get('page');
        fetchPemesanans(pageParam || 1);
    });

    // Select all checkboxes
    $(document).on('change', '#checkbox-all', function() {
        $('.checkbox-row').prop('checked', this.checked);
    });
    $(document).on('change', '.checkbox-row', function() {
        const allChecked = $('.checkbox-row').length === $('.checkbox-row:checked').length;
        $('#checkbox-all').prop('checked', allChecked);
    });

    // Single delete via AJAX
    $(document).on('click', '.delete-pemesanan', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Hapus Pemesanan?',
            text: 'Data ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseUrl + '/single/' + id,
                    type: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success(response) {
                        if (response.success) {
                            row.fadeOut(300, () => row.remove());
                            Swal.fire('Dihapus!', response.message, 'success');
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error() {
                        Swal.fire('Error', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });

    // Bulk delete
    $(document).on('submit', '#deleteFormPemesanan', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Semua Pemesanan Terpilih?',
            text: 'Data terpilih akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                const form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success(response) {
                        if (response.success) {
                            Swal.fire('Dihapus!', response.message, 'success');
                            fetchPemesanans(1);
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error() {
                        Swal.fire('Error', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });
});
