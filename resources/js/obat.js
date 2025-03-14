    // Global Variables
    let sortBy = 'id';
    let sortOrder = 'asc';
    let perPage = 10;

    // Initialize the account management parameters from the URL
    function initObats() {
        const queryParams = new URLSearchParams(window.location.search);
        sortBy = queryParams.get('sortBy') || 'kode_obat    ';
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
    initObats();

    // Event listener to reinitialize when triggered (for AJAX content load)
    $(document).on("obats:init", function () {
        initObats();
        fetchObats(1);  // Fetch users after initialization
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
    function fetchObats(page = 1) {
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
                $('#obatTableContainer').html(response.html);
                updateUrl(page);
            },
            error: function () {
                alert('Error fetching obat data.');
            }
        });
    }

    // Form submission to fetch users based on search input
    $(document).on('submit', '#searchForm', function (e) {
        e.preventDefault();
        fetchObats(1);
    });

    // Toggle sorting order and fetch users
    $(document).on('click', '#toggleSortOrder', function () {
        sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
        $(this).toggleClass('bg-gray-200');
        fetchObats(1);
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
        fetchObats(1);
        $('#sortByPopup').fadeOut(200);
    });

    // Change the number of items per page and fetch users
    $(document).on('change', '#perPage', function () {
        perPage = $(this).val();
        fetchObats(1);
    });

    // Handle pagination link clicks
    $(document).on('click', '#obatTableContainer nav a', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');
        if (href) {
            const url = new URL(href, window.location.origin);
            const page = url.searchParams.get('page') || 1;
            fetchObats(page);
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
    $(document).on('click', '.delete-obat', function (e) {
        e.preventDefault();

        let idObat = $(this).data('id');  // Ambil ID obat
        let row = $(this).closest('tr');  // Ambil baris tabel

        // Tampilkan SweetAlert untuk konfirmasi hapus
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data obat akan dihapus secara permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/obats/single/' + idObat,
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
                                text: 'Obat berhasil dihapus.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus obat.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan. Pastikan obat masih ada.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });


    $(document).on('submit', '#deleteFormObat', function (e) {
        e.preventDefault(); // Cegah submit form secara tradisional

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Obat-obat yang dipilih akan dihapus secara permanen',
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
                            // Refresh tabel obat via AJAX (misalnya, panggil fungsi fetchObats)
                            fetchObats(1);
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
                            text: 'Terjadi kesalahan saat menghapus obat.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
