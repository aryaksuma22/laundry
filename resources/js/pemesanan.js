/**
 * pemesanan.js
 *
 * Handles dynamic interactions for the Admin Pemesanan (Orders) index page.
 * Features: AJAX table loading, sorting, pagination, search, filtering,
 * selection, single/bulk delete, inline status update, and Quick View Modal.
 */

// --- Global State Variables ---
let sortBy = 'tanggal_pesan';    // Default sort column
let sortOrder = 'desc';           // Default sort direction
let perPage = 10;                 // Default items per page
let baseUrl = '';                 // Base URL for the pemesanan index route
let debounceTimer;                // Timer ID for debouncing search input
const DEBOUNCE_DELAY = 400;       // Delay in milliseconds for search debounce

// --- Flowbite Modal Instance ---
let quickViewModalInstance = null; // Holds the initialized Flowbite Modal object

/**
 * Initializes essential page elements, states, and the Quick View Modal.
 * This function should be called on document ready and potentially on AJAX content reloads
 * if the modal structure or its trigger buttons are part of the reloaded content.
 */
function initializePemesananPage() {
    console.log('Initializing Pemesanan Page...');

    // 1. Determine Base URL
    // Prioritize a globally set URL from Blade, then try to infer, then fallback.
    if (window.PEMESANAN_BASE_URL) {
        baseUrl = window.PEMESANAN_BASE_URL;
    } else if (window.location.pathname.includes('/pemesanan')) {
        baseUrl = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/pemesanan') + '/pemesanan'.length).replace(/\/index$/, '').replace(/\/$/, "");
    } else {
        baseUrl = '/pemesanan'; // Default fallback
        console.warn("PEMESANAN_BASE_URL not found or path inference failed. Using default:", baseUrl);
    }
    console.log('Base URL set to:', baseUrl);

    // 2. Initialize UI states from URL parameters (Sort, Filter, PerPage, Search)
    const queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || 'tanggal_pesan';
    sortOrder = queryParams.get('sortOrder') || 'desc';
    perPage = queryParams.get('perPage') || 10;
    const currentSearch = queryParams.get('search') || '';
    const filterMetode = queryParams.get('filter_metode') || '';
    const filterLayanan = queryParams.get('filter_layanan') || '';
    const filterStatus = queryParams.get('filter_status') || '';
    const filterStatusBayar = queryParams.get('filter_status_bayar') || '';

    $('#sort-byPemesanan').val(sortBy);
    $('#sort-order').val(sortOrder);
    $('#perPagePemesanan').val(perPage);
    $('#search').val(currentSearch);
    $('#filter_metode').val(filterMetode);
    $('#filter_layanan').val(filterLayanan);
    $('#filter_status').val(filterStatus);
    $('#filter_status_bayar').val(filterStatusBayar);

    const initialSortByTextEl = $('#sortByPopupPemesanan .sort-optionPemesanan[data-sortby="' + sortBy + '"]');
    const initialSortByText = initialSortByTextEl.length ? initialSortByTextEl.text() : 'Tgl Pesan';
    $('#sortByButtonPemesananText').text('Sort By: ' + initialSortByText);
    $('#toggleSortOrderPemesanan svg').toggleClass('rotate-180', sortOrder === 'desc'); // Corrected: rotate if desc

    // 3. Initialize Flowbite Quick View Modal
    const modalElement = document.getElementById('quickViewModal');
    if (modalElement) {
        if (typeof Modal !== 'undefined') { // Check if Flowbite's Modal class is available
            const modalOptions = {
                placement: 'center-center', // Optional: customize as needed
                backdrop: 'static', // Matches your data-modal-backdrop="static"
                closable: true, // Essential for data-modal-hide and ESC to work
                // onHide: () => { console.log('Quick view modal is hidden.'); },
                // onShow: () => { console.log('Quick view modal is shown.'); }
            };
            quickViewModalInstance = new Modal(modalElement, modalOptions);
            console.log('Flowbite Quick View Modal instance initialized.');
        } else {
            console.error("Flowbite 'Modal' class not found. Ensure Flowbite is installed via NPM, imported in your main JS (e.g., app.js), and Vite is bundling correctly.");
        }
    } else {
        console.warn("#quickViewModal element not found in the DOM. Cannot initialize modal.");
    }
    $('body').data('pemesanan-initialized', true); // Mark as initialized
}

/**
 * Updates the browser's URL query string using history.pushState.
 */
function updateUrl(page) {
    if (!baseUrl) {
        console.warn("Cannot update URL: baseUrl is not set.");
        return;
    }
    const queryParams = new URLSearchParams({
        search: $('#search').val() || '',
        sortBy: sortBy,
        sortOrder: sortOrder,
        perPage: perPage,
        page: page || 1, // Ensure page is always set
        filter_metode: $('#filter_metode').val() || '',
        filter_layanan: $('#filter_layanan').val() || '',
        filter_status: $('#filter_status').val() || '',
        filter_status_bayar: $('#filter_status_bayar').val() || ''
    });

    const pathOnlyBaseUrl = baseUrl.split('?')[0];
    // Ensure there's a slash before the query string if pathOnlyBaseUrl is not just "/"
    const queryString = queryParams.toString();
    let newUrl = pathOnlyBaseUrl;
    if (pathOnlyBaseUrl !== '/' && !pathOnlyBaseUrl.endsWith('/')) {
        newUrl += '/';
    }
    newUrl += `?${queryString}`;

    window.history.pushState({ path: newUrl }, '', newUrl);
}


/**
 * Fetches pemesanan data from the server via an AJAX GET request.
 */
function fetchPemesanans(page = 1) {
    if (!baseUrl) {
        console.error("Base URL is not set. Cannot fetch Pemesanan data.");
        $('#pemesananTableContainer').html('<div class="text-center p-5 text-red-500">Kesalahan: URL dasar tidak diatur.</div>');
        return;
    }

    const requestData = {
        search: $('#search').val() || '',
        sortBy: sortBy,
        sortOrder: sortOrder,
        perPage: perPage,
        page: page,
        filter_metode: $('#filter_metode').val() || '',
        filter_layanan: $('#filter_layanan').val() || '',
        filter_status: $('#filter_status').val() || '',
        filter_status_bayar: $('#filter_status_bayar').val() || ''
    };
    const container = $('#pemesananTableContainer');

    console.log(`Fetching Pemesanan Data: URL='${baseUrl}', Params=`, requestData);


    $.ajax({
        url: baseUrl, // Controller index method handles these params
        type: 'GET',
        dataType: 'json', // Expect JSON response containing HTML
        data: requestData,
        beforeSend: function () {
            if ($('#loadingOverlayPemesanan').length === 0) { // Add overlay only if not present
                container.css({ 'position': 'relative', 'min-height': '250px' }); // Ensure container can hold overlay
                const loadingHtml = `
                    <div id="loadingOverlayPemesanan" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-20 rounded-lg">
                        <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>`;
                container.append(loadingHtml);
            }
        },
        success: function (response) {
            $('#loadingOverlayPemesanan').remove();
            if (response && response.html) {
                container.html(response.html);
                // Check if the call was from popstate to avoid double history update
                const isPopState = typeof event !== 'undefined' && event instanceof PopStateEvent && event.target === window;
                if (!isPopState) {
                    updateUrl(page);
                }
            } else {
                container.html('<div class="text-center p-5 text-red-500">Gagal memuat data atau format respon salah.</div>');
                console.error("Invalid response structure for Pemesanan:", response);
            }
        },
        error: function (xhr, status, error) {
            $('#loadingOverlayPemesanan').remove();
            container.html('<div class="text-center p-5 text-red-500">Terjadi kesalahan saat memuat data. Silakan coba lagi. Detail: ' + xhr.status + ' ' + error + '</div>');
            console.error("Error fetching Pemesanan data:", xhr, status, error);
        }
    });
}

// --- Page Load ---
$(document).ready(function () {
    // Initialize only if on the relevant page and not already initialized
    if ($('#pemesananTableContainer').length > 0 && !$('body').data('pemesanan-initialized')) {
        initializePemesananPage();
    }
});

// --- Event Listener for Dynamic Content (if you use such a mechanism) ---
// This is if you have a custom event like 'pemesanans:init' that fires after
// a part of the page (like the main content area) is loaded via AJAX by another script.
$(document).on("pemesanans:init", function () {
    console.log('Event pemesanans:init received.');
    if ($('#pemesananTableContainer').length > 0) {
        initializePemesananPage(); // Re-initialize, including modal
        const queryParams = new URLSearchParams(window.location.search);
        fetchPemesanans(queryParams.get('page') || 1); // Fetch initial data if needed
    }
});

// --- Event Listeners for UI Interactions ---

// Search Input (Debounced)
$(document).on('input', '#search', function () {
    if (!$('#pemesananTableContainer').length) return;
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => { fetchPemesanans(1); }, DEBOUNCE_DELAY);
});
$(document).on('submit', '#searchFormPemesanan', function (e) { // Prevent form submission if it's a form
    e.preventDefault();
});


// Sorting Dropdown Toggle
$(document).on('click', '#sortByButtonPemesanan', function (e) {
    e.stopPropagation(); // Prevent click from closing the dropdown immediately
    $('#sortByPopupPemesanan').toggleClass('hidden');
});

// Sort By Option Click
$(document).on('click', '.sort-optionPemesanan', function (e) {
    e.preventDefault();
    const newSortBy = $(this).data('sortby');
    if (newSortBy && newSortBy !== sortBy) {
        sortBy = newSortBy;
        $('#sortByButtonPemesananText').text('Sort By: ' + $(this).text());
        $('#sort-byPemesanan').val(sortBy); // Update hidden input if you use it
        fetchPemesanans(1);
    }
    $('#sortByPopupPemesanan').addClass('hidden'); // Hide dropdown
});

// Sort Order Toggle
$(document).on('click', '#toggleSortOrderPemesanan', function () {
    sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
    $('#sort-order').val(sortOrder); // Update hidden input if you use it
    $(this).find('svg').toggleClass('rotate-180');
    fetchPemesanans(1);
});

// Hide Sort Dropdown on clicking outside
$(document).on('click', function (e) {
    const $sortByButton = $('#sortByButtonPemesanan');
    const $sortByPopup = $('#sortByPopupPemesanan');
    if (!$sortByButton.is(e.target) && $sortByButton.has(e.target).length === 0 &&
        !$sortByPopup.is(e.target) && $sortByPopup.has(e.target).length === 0) {
        $sortByPopup.addClass('hidden');
    }
});


// Filters Change
$(document).on('change', '#filter_metode, #filter_layanan, #filter_status, #filter_status_bayar', function () {
    fetchPemesanans(1);
});

// Reset Filters Button
$(document).on('click', '#resetFiltersButton', function () {
    $('#filter_metode, #filter_layanan, #filter_status, #filter_status_bayar').val(''); // Clear dropdowns
    $('#search').val(''); // Optionally clear search as well
    fetchPemesanans(1); // Fetch with default filters
});

// Per Page Change
$(document).on('change', '#perPagePemesanan', function () {
    perPage = $(this).val();
    fetchPemesanans(1);
});

// Pagination Links Click (delegated to the container for dynamically loaded links)
$(document).on('click', '#pemesananTableContainer .pagination a', function (e) {
    e.preventDefault();
    const href = $(this).attr('href');
    if (!href || href === '#' || $(this).parent().hasClass('disabled') || $(this).parent().hasClass('active')) {
        return; // Do nothing for invalid, disabled, or active links
    }
    try {
        const url = new URL(href);
        const page = url.searchParams.get('page');
        if (page && !isNaN(page)) {
            fetchPemesanans(page);
        } else {
            console.warn('Pagination link does not have a valid page parameter:', href);
        }
    } catch (error) {
        console.error("Error processing pagination URL:", href, error);
    }
});

// Checkbox All/Row Selection
$(document).on('change', '#checkbox-all', function () {
    const isChecked = $(this).prop('checked');
    $('#pemesananTableContainer .checkbox-row').prop('checked', isChecked)
        .closest('tr').toggleClass('bg-indigo-50', isChecked);
});
$(document).on('change', '#pemesananTableContainer .checkbox-row', function () {
    $(this).closest('tr').toggleClass('bg-indigo-50', $(this).prop('checked'));
    const totalCheckboxes = $('#pemesananTableContainer .checkbox-row').length;
    const checkedCheckboxes = $('#pemesananTableContainer .checkbox-row:checked').length;
    $('#checkbox-all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
});

// --- Quick View Modal ---
$(document).on('click', '.quick-view-btn', function (e) {
    e.preventDefault();
    const pemesananId = $(this).data('id');
    const $modalContent = $('#quickViewModalContent');

    if (!pemesananId) {
        Swal.fire('Error', 'ID Pesanan tidak ditemukan.', 'error');
        return;
    }
    if (!baseUrl) {
        Swal.fire('Error', 'Base URL tidak terkonfigurasi.', 'error');
        return;
    }
    if (!quickViewModalInstance) {
        Swal.fire('Error', 'Komponen modal tidak siap. Coba segarkan halaman.', 'error');
        console.error("QuickViewModalInstance is not initialized!");
        return;
    }

    // 1. Show loading spinner in modal content area
    $modalContent.html(
        `<div class="text-center py-10">
            <svg class="animate-spin h-8 w-8 text-gray-500 dark:text-gray-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Memuat detail...</p>
        </div>`
    );

    // 2. Show the modal using the Flowbite instance
    quickViewModalInstance.show();

    // 3. Fetch modal content via AJAX
    const ajaxUrl = `${baseUrl.replace(/\/$/, "")}/${pemesananId}/quick-view`;
    $.ajax({
        url: ajaxUrl,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success && response.html) {
                $modalContent.html(response.html);
                // Flowbite should handle the data-modal-hide attributes on buttons
                // inside the newly loaded HTML because the parent modal instance is already active.
            } else {
                $modalContent.html(`<div class="p-6 text-center text-red-500">${response.message || 'Gagal memuat detail.'}</div>`);
            }
        },
        error: function (xhr) {
            $modalContent.html(`<div class="p-6 text-center text-red-500">Error: ${xhr.status}. Gagal memuat detail.</div>`);
            console.error("Quick view AJAX error:", xhr.responseText);
            // Optionally, hide modal on error:
            // if (quickViewModalInstance.isVisible()) {
            //     quickViewModalInstance.hide();
            // }
        }
    });
});


// --- Inline Status Update Handler ---
$(document).on('change', '.status-dropdown', function () {
    const $select = $(this);
    const pemesananId = $select.data('id');
    const newStatus = $select.val();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const $spinner = $select.closest('td').find('.status-spinner-' + pemesananId); // Ensure spinner exists with this class

    // Store original value if not already stored, for reverting on error
    if (typeof $select.data('original-value') === 'undefined') {
        $select.data('original-value', $select.find('option[selected]').val() || $select.find('option:first').val());
    }
    const originalStatus = $select.data('original-value');

    // Store old badge classes for reverting on error
    let oldClassesArray = [];
    const currentClasses = $select.attr('class') || "";
    currentClasses.split(' ').forEach(function (cls) {
        if (cls.startsWith('bg-') || cls.startsWith('text-')) { oldClassesArray.push(cls); }
    });
    const oldBadgeClasses = oldClassesArray.join(' ');


    if (!pemesananId || typeof newStatus === 'undefined' || !csrfToken || !baseUrl) {
        Swal.fire('Error', 'Data tidak lengkap untuk update status.', 'error');
        return;
    }

    $spinner.removeClass('hidden');
    $select.prop('disabled', true);

    $.ajax({
        url: `${baseUrl.replace(/\/$/, "")}/${pemesananId}/status`,
        type: 'PATCH',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        data: { status_pesanan: newStatus },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                Swal.fire({ toast: true, icon: 'success', title: response.message || 'Status diperbarui!', position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });

                // Update badge class
                if (oldBadgeClasses) $select.removeClass(oldBadgeClasses);
                if (response.status_badge_class) $select.addClass(response.status_badge_class);

                $select.data('original-value', newStatus); // Update original value to current
            } else {
                Swal.fire('Gagal', response.message || 'Gagal memperbarui status.', 'error');
                $select.val(originalStatus); // Revert to original status
                // Revert badge class
                if (response.status_badge_class) $select.removeClass(response.status_badge_class); // Remove newly added (failed) class
                if (oldBadgeClasses) $select.addClass(oldBadgeClasses); // Add back original classes
            }
        },
        error: function (xhr) {
            let errorMsg = 'Terjadi kesalahan server.';
            if (xhr.responseJSON && xhr.responseJSON.message) { errorMsg = xhr.responseJSON.message; }
            else if (xhr.statusText) { errorMsg = xhr.statusText; }
            Swal.fire('Error ' + xhr.status, errorMsg, 'error');
            $select.val(originalStatus); // Revert to original status

            // Revert badge classes more carefully on AJAX error
            const currentSelectClassesOnError = $select.attr('class') || "";
            let newAppliedClassesOnError = [];
            currentSelectClassesOnError.split(' ').forEach(cls => {
                if (cls.startsWith('bg-') || cls.startsWith('text-')) newAppliedClassesOnError.push(cls);
            });
            if (newAppliedClassesOnError.length > 0) $select.removeClass(newAppliedClassesOnError.join(' '));
            if (oldBadgeClasses) $select.addClass(oldBadgeClasses);

            console.error("Inline status update error:", xhr.responseText);
        },
        complete: function () {
            $spinner.addClass('hidden');
            $select.prop('disabled', false);
        }
    });
});


// --- Delete Actions (Single and Bulk) ---
// Single Delete
$(document).on('click', '.delete-pemesanan', function (e) {
    e.preventDefault();
    const $button = $(this);
    const idPemesanan = $button.data('id');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (!idPemesanan || !csrfToken || !baseUrl) {
        Swal.fire('Error', 'Data tidak lengkap untuk menghapus.', 'error');
        return;
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Hapus pemesanan (ID: ${idPemesanan})? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${baseUrl.replace(/\/$/, "")}/single/${idPemesanan}`,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $button.closest('tr').fadeOut(400, function () { $(this).remove(); });
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 1500, showConfirmButton: false });
                        // Optionally, refresh table if it becomes empty
                        if ($('#pemesananTableContainer tbody tr').not(':has(td[colspan])').length === 0) {
                            fetchPemesanans(1);
                        }
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus pesanan.', 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', `Terjadi kesalahan: ${xhr.status} ${xhr.statusText}`, 'error');
                    console.error("Single delete error:", xhr.responseText);
                }
            });
        }
    });
});

// Bulk Delete
$(document).on('click', '#bulkDeleteButton', function (e) {
    e.preventDefault();
    const selectedIds = $('#pemesananTableContainer .checkbox-row:checked').map(function () { return $(this).val(); }).get();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (selectedIds.length === 0) {
        Swal.fire('Perhatian', 'Pilih setidaknya satu item untuk dihapus.', 'warning');
        return;
    }
    if (!csrfToken || !baseUrl) {
        Swal.fire('Error', 'Konfigurasi tidak lengkap untuk penghapusan massal.', 'error');
        return;
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Hapus ${selectedIds.length} item terpilih? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${baseUrl.replace(/\/$/, "")}/mass-delete`,
                type: 'POST', // Laravel uses POST for mass delete with _method spoofing
                data: {
                    _token: csrfToken,
                    _method: 'DELETE', // Method spoofing
                    ids: selectedIds
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                        fetchPemesanans(1); // Refresh table data
                        $('#checkbox-all').prop('checked', false); // Uncheck master checkbox
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus item terpilih.', 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', `Terjadi kesalahan: ${xhr.status} ${xhr.statusText}`, 'error');
                    console.error("Bulk delete error:", xhr.responseText);
                }
            });
        }
    });
});


// Browser History Navigation (Back/Forward Buttons)
window.addEventListener('popstate', function (event) {
    // Check if we are on a page that uses this script and was initialized
    if ($('#pemesananTableContainer').length > 0 && $('body').data('pemesanan-initialized')) {
        console.log('Popstate event triggered for Pemesanan. Current URL:', window.location.href);
        // Re-initialize states from URL and fetch data.
        // The initializePemesananPage function will read params from the new URL.
        initializePemesananPage();
        const queryParams = new URLSearchParams(window.location.search);
        fetchPemesanans(queryParams.get('page') || 1);
    }
});
