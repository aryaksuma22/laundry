
let sortBy = 'tanggal_pesan';
let sortOrder = 'desc';
let perPage = 10;
let baseUrl = '';

function initPemesanans() {
    baseUrl = window.location.pathname.replace(/\/$/, "");
    console.log('Pemesanan Initialized. Base URL set to:', baseUrl);

    const queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || 'tanggal_pesan';
    sortOrder = queryParams.get('sortOrder') || 'desc';
    perPage = queryParams.get('perPage') || 10;

    // Update form elements if they exist
    if ($('#sort-byPemesanan').length) $('#sort-byPemesanan').val(sortBy);
    if ($('#sort-order').length) $('#sort-order').val(sortOrder);
    if ($('#perPagePemesanan').length) $('#perPagePemesanan').val(perPage);
    if ($('#search').length) $('#search').val(queryParams.get('search') || '');

    // Update sort button text
    const initialSortByText = $('#sortByPopupPemesanan .sort-optionPemesanan[data-sortby="' + sortBy + '"]').text();
    if (initialSortByText && $('#sortByButtonPemesananText').length) {
        $('#sortByButtonPemesananText').text('Sort By: ' + initialSortByText);
    } else if ($('#sortByButtonPemesananText').length) {
        $('#sortByButtonPemesananText').text('Sort By: Tgl Pesan');
    }

    // Update sort order icon
    if ($('#toggleSortOrderPemesanan svg').length) {
        if (sortOrder === 'asc') {
            $('#toggleSortOrderPemesanan svg').removeClass('rotate-180');
        } else {
            $('#toggleSortOrderPemesanan svg').addClass('rotate-180');
        }
    }
}

// Initial setup when the script first loads (e.g., direct access to /pemesanan)
// We check if jQuery is loaded and if we are on the correct path initially
if (window.location.pathname.includes('/pemesanan') && typeof $ !== 'undefined') {
    $(document).ready(function () {
        if (!$('body').data('pemesanan-initialized')) {
            console.log('Direct page load initialization for Pemesanan.');
            initPemesanans();
            fetchPemesanans(new URLSearchParams(window.location.search).get('page') || 1);
            $('body').data('pemesanan-initialized', true);
        }
    });
}

// Listen for the custom event triggered by sidebarAjax.js after loading content
$(document).on("pemesanans:init", function () {
    console.log('Event pemesanans:init received. Re-initializing...');
    initPemesanans();
    fetchPemesanans(new URLSearchParams(window.location.search).get('page') || 1);
    $('body').data('pemesanan-initialized', true);
});


// Update browser URL without reloading
function updateUrl(page) {
    const query = new URLSearchParams({
        search: $('#search').val() || '',
        sortBy, sortOrder, perPage, page
    }).toString();
    window.history.replaceState(null, '', baseUrl + '?' + query);
}

// Fetch pemesanan data based on current parameters
// Fetch pemesanan data based on current parameters
// Fetch pemesanan data based on current parameters
function fetchPemesanans(page = 1) {
    const searchVal = $('#search').val() || '';
    console.log("Fetching page:", page, "SortBy:", sortBy, "SortOrder:", sortOrder, "PerPage:", perPage, "Search:", searchVal, "URL being used:", baseUrl);

    const requestData = {
        search: searchVal, sortBy: sortBy, sortOrder: sortOrder, perPage: perPage, page: page
    };
    console.log("Sending AJAX data:", requestData);

    if (!baseUrl) {
        console.error("Base URL is not set. Cannot fetch data.");
        $('#pemesananTableContainer').html('<div class="text-center p-5 text-red-500">Kesalahan: URL dasar tidak diatur. Tidak dapat memuat data.</div>');
        return;
    }

    const container = $('#pemesananTableContainer');

    $.ajax({
        url: baseUrl,
        type: 'GET',
        dataType: 'json',
        data: requestData,
        beforeSend: function () {
            // ***** ADD OVERLAY & SVG SPINNER *****

            // Prevent adding multiple overlays if one already exists
            if ($('#loadingOverlayPemesanan').length > 0) {
                console.log('Loading overlay already present. Skipping addition.');
                return;
            }

            // Ensure container is relative (important for absolute positioning of overlay)
            if (container.css('position') !== 'relative') {
                container.css('position', 'relative');
            }
            // Ensure min-height (helps visibility if table is initially empty)
            container.css('min-height', '250px');

            // *** Use a standard SVG Spinner with animate-spin ***
            const loadingHtml = `
                <div id="loadingOverlayPemesanan" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-20 rounded-lg" aria-label="Loading...">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            `;
            // Append the overlay to the container
            container.append(loadingHtml);
            console.log('Loading overlay appended.');
        },
        success: function (response) {
            // ***** REMOVE OVERLAY, THEN UPDATE CONTENT *****
            const overlay = $('#loadingOverlayPemesanan');
            if (overlay.length) {
                overlay.remove();
                console.log('Loading overlay removed (success).');
            } else {
                console.log('Loading overlay not found to remove (success).');
            }


            if (response && response.html) {
                container.html(response.html); // Update content
                updateUrl(page);
            } else {
                container.html('<div class="text-center p-5 text-red-500">Gagal memuat data atau format respon salah.</div>');
                console.error("Invalid response structure:", response);
            }
            // Optional: Reset min-height
            // container.css('min-height', '');
        },
        error: function (xhr, status, error) {
            // ***** REMOVE OVERLAY, THEN SHOW ERROR *****
            const overlay = $('#loadingOverlayPemesanan');
            if (overlay.length) {
                overlay.remove();
                console.log('Loading overlay removed (error).');
            } else {
                console.log('Loading overlay not found to remove (error).');
            }


            container.html('<div class="text-center p-5 text-red-500">Terjadi kesalahan saat mengambil data. Silakan coba lagi.</div>');
            console.error("Error fetching Pemesanan data:", status, error, xhr.responseText);
            // Optional: Reset min-height
            // container.css('min-height', '');
        }
        // complete: function() {
        //     // Alternative location to remove overlay, always runs after success/error
        //     $('#loadingOverlayPemesanan').remove();
        //     console.log('Loading overlay removed (complete).');
        // }
    });
}



// --- Event Listeners ---

// Search: Trigger fetch on form submit
$(document).on('submit', '#searchFormPemesanan', function (e) {
    e.preventDefault();
    fetchPemesanans(1);
});

// Search: Trigger fetch also when clearing the search input (optional but good UX)
$(document).on('search', '#search', function (e) {
    if ($(this).val() === '') {
        fetchPemesanans(1);
    }
});


// Toggle sort order: Update sortOrder and fetch data
$(document).on('click', '#toggleSortOrderPemesanan', function () {

    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
    if ($('#sort-order').length) {
        $('#sort-order').val(sortOrder);
    }
    // Update icon rotation
    $(this).find('svg').toggleClass('rotate-180');
    fetchPemesanans(1);
});

// Select the sorting option from the dropdown: Update sortBy and fetch data
$(document).on('click', '#sortByPopupPemesanan .sort-optionPemesanan', function (e) {
    e.preventDefault();
    const newSortBy = $(this).data('sortby');
    if (newSortBy && newSortBy !== sortBy) {
        sortBy = newSortBy;
        if ($('#sort-byPemesanan').length) {
            $('#sort-byPemesanan').val(sortBy);
        }
        // Update sort button text
        if ($('#sortByButtonPemesananText').length) {
            $('#sortByButtonPemesananText').text('Sort By: ' + $(this).text());
        }
        fetchPemesanans(1);
    }
    $('#sortByPopupPemesanan').addClass('hidden');
});


// Toggle sort popup visibility
$(document).on('click', '#sortByButtonPemesanan', function (e) {
    e.stopPropagation();
    $('#sortByPopupPemesanan').toggleClass('hidden');
});

// Close sort popup when clicking anywhere else on the document
$(document).on('click', function (e) {
    if (!$('#sortByPopupPemesanan').hasClass('hidden') && !$(e.target).closest('#sortByButtonPemesanan, #sortByPopupPemesanan').length) {
        $('#sortByPopupPemesanan').addClass('hidden');
    }
});


// Change entries per page: Update perPage and fetch data
$(document).on('change', '#perPagePemesanan', function () {
    perPage = $(this).val();
    fetchPemesanans(1);
});

// Handle pagination link clicks: Extract page number and fetch data
$(document).on('click', '#pemesananTableContainer nav[role="navigation"] a', function (e) {
    e.preventDefault(); // Prevent default link navigation FIRST!

    const href = $(this).attr('href');
    const rel = $(this).attr('rel'); // Check for 'next' or 'prev' if needed, though URL parsing is better

    // --- Validation ---
    // 1. Check if it's a valid link with an href
    if (!href || href === '#') {
        console.log('Pagination click ignored: No valid href found.');
        return; // Ignore clicks on elements without a proper href (e.g., maybe a styled span)
    }

    // 2. Check if the link is disabled (often indicated by parent li class in Bootstrap/Tailwind themes)
    //    Adjust the class check if your specific pagination theme uses something different.
    //    Common classes: 'disabled', 'cursor-not-allowed', 'opacity-50' etc.
    if ($(this).parent().hasClass('disabled') || $(this).hasClass('disabled') || $(this).hasClass('cursor-not-allowed')) {
        console.log('Pagination click ignored: Link is disabled.');
        return;
    }
    // 3. Check if it's the active/current page (often a span or an 'a' without href/specific classes)
    //    The !href check above helps, but sometimes active page is an 'a' with href.
    //    Check for common active classes on the parent list item.
    if ($(this).parent().hasClass('active') || $(this).data('active') === true /* Example if using data attributes */) {
        console.log('Pagination click ignored: Link is the current page.');
        return;
    }
    // --- End Validation ---


    console.log('Intercepted pagination click:', href); // Debug log

    try {
        // Use the full URL from the pagination link's href
        const url = new URL(href);
        // Extract the 'page' query parameter
        const page = url.searchParams.get('page');

        if (page && !isNaN(page)) { // Ensure 'page' exists and is a number
            console.log('Fetching data for page:', page);
            fetchPemesanans(page); // Fetch the specific page using AJAX
        } else {
            console.warn('Could not extract a valid page number from pagination link:', href);
            // Fallback or error handling? For now, just warning.
            // If it still reloads here, there might be an issue in fetchPemesanans or URL update logic.
        }
    } catch (error) {
        console.error("Error processing pagination URL:", href, error);
        // Prevent potential reload if URL parsing fails? The preventDefault should still hold.
    }
});

// --- Selection Logic ---
// Select/Deselect all rows
$(document).on('change', '#checkbox-all', function () {
    const isChecked = $(this).prop('checked');
    $('.checkbox-row').prop('checked', isChecked);
    $('.checkbox-row').closest('tr').toggleClass('bg-indigo-50', isChecked);
});

// Select/Deselect a single row
$(document).on('change', '.checkbox-row', function () {
    const totalCheckboxes = $('.checkbox-row').length;
    const checkedCheckboxes = $('.checkbox-row:checked').length;
    $('#checkbox-all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
    $(this).closest('tr').toggleClass('bg-indigo-50', $(this).prop('checked'));
});

// --- Delete Logic ---

// Single Delete: Handle click on delete button in a row
$(document).on('click', '.delete-pemesanan', function (e) {
    e.preventDefault();

    let idPemesanan = $(this).data('id');
    let row = $(this).closest('tr'); // Get the table row element
    const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

    // Basic validation
    if (!idPemesanan) {
        console.error("Delete button missing data-id attribute.");
        Swal.fire('Error', 'ID Pemesanan tidak ditemukan pada tombol.', 'error');
        return;
    }
    if (!csrfToken) {
        console.error("CSRF token meta tag not found.");
        Swal.fire('Error', 'Kesalahan konfigurasi keamanan. Silakan refresh halaman.', 'error');
        return;
    }

    // Confirmation dialog
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data pemesanan ini akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280', // Gray cancel button
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true // Put confirm button on the right
    }).then((result) => {
        if (result.isConfirmed) {
            // Get the correct delete URL from the route (ensure consistent naming)
            // Assuming the route name is 'pemesanan.destroySingle' and URL is /pemesanans/single/{id}
            // If your route URL is different, adjust this string concatenation.
            const deleteUrl = baseUrl + '/single/' + idPemesanan; // Construct URL based on current baseUrl

            console.log("Attempting single delete:", deleteUrl);

            $.ajax({
                url: deleteUrl,
                type: 'DELETE', // Use DELETE method
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Send CSRF token in headers
                },
                dataType: 'json', // Expect JSON response
                success: function (response) {
                    if (response && response.success) {
                        // Fade out and remove the row
                        row.fadeOut(400, function () {
                            $(this).remove();
                            // Optionally, refresh if table becomes empty
                            if ($('#pemesananTableContainer tbody tr').not(':has(td[colspan])').length === 0) { // Check for actual data rows
                                fetchPemesanans(1); // Refresh to show "No data" message or previous page
                            }
                        });
                        // Success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Pemesanan berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus Pemesanan.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    let errorMsg = 'Terjadi kesalahan saat menghapus.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMsg = 'Data pemesanan tidak ditemukan (404).';
                    } else if (xhr.status === 403) {
                        errorMsg = 'Anda tidak memiliki izin untuk melakukan aksi ini (403).';
                    }
                    Swal.fire('Error', errorMsg, 'error');
                    console.error("Single delete error:", status, error, xhr.responseText);
                }
            });
        }
    });
});

// Multiple Delete: Handle form submission for bulk deletion
$(document).on('click', '#bulkDeleteButton', function (e) {
    e.preventDefault(); // Good practice for button clicks

    let selectedIds = $('.checkbox-row:checked').map(function () { // Get IDs from checkboxes in the table container
        return $(this).val();
    }).get();

    // Check if any checkboxes are selected
    if (selectedIds.length === 0) {
        Swal.fire('Perhatian', 'Pilih setidaknya satu pemesanan untuk dihapus.', 'warning');
        return;
    }

    // Get CSRF token from meta tag (ensure it exists in your main layout)
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error("CSRF token meta tag not found.");
        Swal.fire('Error', 'Kesalahan konfigurasi keamanan (CSRF). Silakan refresh halaman.', 'error');
        return;
    }

    // Define the target URL (use the correct route for mass destroy)
    // Check your web.php for the route named 'pemesanan.massDestroy'
    // It's likely '/pemesanan/mass-delete' or similar
    const bulkDeleteUrl = baseUrl + '/mass-delete'; // Adjust '/mass-delete' if your route path is different

    // Confirmation dialog
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus ${selectedIds.length} pemesanan yang dipilih secara permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Manually construct data for AJAX
            const requestData = {
                _token: csrfToken,
                _method: 'DELETE', // Method spoofing
                ids: selectedIds   // Array of selected IDs
            };

            console.log("Attempting bulk delete via button click:", bulkDeleteUrl, "Data:", requestData);

            $.ajax({
                url: bulkDeleteUrl,
                type: 'POST', // Use POST for method spoofing
                data: requestData,
                dataType: 'json',
                success: function (response) {
                    if (response && response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || `${selectedIds.length} Pemesanan berhasil dihapus.`,
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                        fetchPemesanans(1); // Refresh table to page 1
                        $('#checkbox-all').prop('checked', false); // Uncheck the master checkbox
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus pemesanan.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    let errorMsg = 'Terjadi kesalahan saat menghapus pemesanan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        errorMsg = 'Anda tidak memiliki izin untuk melakukan aksi ini (403).';
                    }
                    Swal.fire('Error', errorMsg, 'error');
                    console.error("Bulk delete error:", status, error, xhr.responseText);
                }
            });
        }
    });
});
