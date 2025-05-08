/**
 * transaksi.js
 *
 * Handles dynamic interactions for the Admin Transaksi (Transactions) index page.
 * Features: AJAX table loading, sorting, pagination, search-as-you-type (debounced),
 * filtering, selection, single/bulk delete, and back/forward navigation support.
 */

// --- Global State Variables ---
let sortByTransaksi = 'created_at';    // Default sort column for transactions
let sortOrderTransaksi = 'desc';       // Default sort direction
let perPageTransaksi = 10;             // Default items per page
let baseUrlTransaksi = '';             // Base URL for the transaksi index route
let debounceTimerTransaksi;            // Timer ID for debouncing search input
const DEBOUNCE_DELAY_TRANSAKSI = 400; // Delay in milliseconds for search debounce

// --- Initialization Functions ---

/**
 * Initializes the state (sortBy, sortOrder, perPage, search, filters)
 * based on URL query parameters and updates corresponding UI elements for Transaksi.
 */
function initTransaksis() {
    // Determine the base URL (e.g., /transaksis) from the current window location
    baseUrlTransaksi = window.location.pathname.replace(/\/$/, ""); // Assumes URL is like /transaksis
    console.log('Transaksi Initialized. Base URL set to:', baseUrlTransaksi);

    // Read parameters from the current URL's query string
    const queryParams = new URLSearchParams(window.location.search);
    sortByTransaksi = queryParams.get('sortBy') || 'created_at'; // Default for transaksi
    sortOrderTransaksi = queryParams.get('sortOrder') || 'desc';
    perPageTransaksi = queryParams.get('perPage') || 10;
    const currentSearch = queryParams.get('search') || '';
    // --- Define Filters Specific to Transaksi ---
    const filterStatusPembayaran = queryParams.get('filter_status_pembayaran') || '';
    const filterMetodePembayaran = queryParams.get('filter_metode_pembayaran') || '';

    // Update hidden input values (optional, if you use them)
    // $('#sort-byTransaksi').val(sortByTransaksi);
    // $('#sort-orderTransaksi').val(sortOrderTransaksi);

    // Update visible UI elements to match the current state
    // Ensure these IDs match your transaksi index blade file
    $('#perPageTransaksi').val(perPageTransaksi);
    $('#search').val(currentSearch); // Assuming shared search input ID
    $('#filter_status_pembayaran').val(filterStatusPembayaran);
    $('#filter_metode_pembayaran').val(filterMetodePembayaran);


    // Update the "Sort By" button text
    // Ensure IDs and classes match your transaksi index blade file
    const initialSortByText = $('#sortByPopupTransaksi .sort-optionTransaksi[data-sortby="' + sortByTransaksi + '"]').text();
    $('#sortByButtonTransaksiText').text('Sort By: ' + (initialSortByText || 'Tgl Dibuat')); // Adjust default text

    // Update the Sort Order button's icon rotation
    // Ensure ID matches your transaksi index blade file
    $('#toggleSortOrderTransaksi svg').toggleClass('rotate-180', sortOrderTransaksi !== 'asc');
}

// --- Core AJAX and URL Functions ---

/**
 * Updates the browser's URL query string using history.pushState for Transaksi.
 * Reflects the current table state (search, sort, filters, page).
 * @param {number} page - The current page number being displayed.
 */
function updateUrlTransaksi(page) {
    const query = new URLSearchParams({
        search: $('#search').val() || '', // Assuming shared search input ID
        sortBy: sortByTransaksi,
        sortOrder: sortOrderTransaksi,
        perPage: perPageTransaksi,
        page: page,
        // --- Add Filters Specific to Transaksi ---
        filter_status_pembayaran: $('#filter_status_pembayaran').val() || '',
        filter_metode_pembayaran: $('#filter_metode_pembayaran').val() || ''
    }).toString();
    const newUrl = baseUrlTransaksi + '?' + query;

    window.history.pushState({ path: newUrl }, '', newUrl);
    // console.log('Transaksi URL updated to:', newUrl);
}

/**
 * Fetches transaksi data from the server via an AJAX GET request.
 * Sends current state (search, sort, filters, pagination) as query parameters.
 * Updates the '#transaksiTableContainer' with the returned HTML table partial.
 * @param {number} [page=1] - The page number to fetch.
 */
function fetchTransaksis(page = 1) {
    const searchVal = $('#search').val() || ''; // Assuming shared search input ID
    // --- Get Filter Values Specific to Transaksi ---
    const filterStatusPembayaranVal = $('#filter_status_pembayaran').val() || '';
    const filterMetodePembayaranVal = $('#filter_metode_pembayaran').val() || '';

    console.log(`Fetching Transaksi page: ${page} | Sort: ${sortByTransaksi} ${sortOrderTransaksi} | PerPage: ${perPageTransaksi} | Search: ${searchVal} | F_Status: ${filterStatusPembayaranVal} | F_Metode: ${filterMetodePembayaranVal} | URL: ${baseUrlTransaksi}`);

    const requestData = {
        search: searchVal,
        sortBy: sortByTransaksi,
        sortOrder: sortOrderTransaksi,
        perPage: perPageTransaksi,
        page: page,
        // --- Send Filters Specific to Transaksi ---
        filter_status_pembayaran: filterStatusPembayaranVal,
        filter_metode_pembayaran: filterMetodePembayaranVal
    };

    if (!baseUrlTransaksi) {
        console.error("Base URL Transaksi is not set. Cannot fetch data.");
        $('#transaksiTableContainer').html('<div class="text-center p-5 text-red-500">Kesalahan: URL dasar Transaksi tidak diatur.</div>');
        return;
    }

    const container = $('#transaksiTableContainer'); // Target specific container

    $.ajax({
        url: baseUrlTransaksi, // Target the base route (e.g., /transaksis)
        type: 'GET',
        dataType: 'json',
        data: requestData,
        beforeSend: function () {
            if ($('#loadingOverlayTransaksi').length > 0) return; // Check specific overlay
            container.css({ 'position': 'relative', 'min-height': '250px' });
            const loadingHtml = `
                <div id="loadingOverlayTransaksi" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-20 rounded-lg" aria-label="Loading...">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>`;
            container.append(loadingHtml);
        },
        success: function (response) {
            $('#loadingOverlayTransaksi').remove(); // Remove specific overlay
            if (response && response.html) {
                container.html(response.html);
                const isPopState = typeof event !== 'undefined' && event instanceof PopStateEvent;
                if (!isPopState) {
                    updateUrlTransaksi(page); // Update URL for transaksi
                }
                console.log('Transaksi Table updated successfully.');
            } else {
                container.html('<div class="text-center p-5 text-red-500">Gagal memuat data transaksi atau format respon salah.</div>');
                console.error("Invalid response structure for Transaksi:", response);
            }
        },
        error: function (xhr, status, error) {
            $('#loadingOverlayTransaksi').remove(); // Remove specific overlay
            container.html('<div class="text-center p-5 text-red-500">Terjadi kesalahan saat memuat data transaksi. Silakan coba lagi.</div>');
            console.error("Error fetching Transaksi data:", status, error, xhr.responseText);
        }
    });
}

// --- Page Load and Initialization ---

// Handles DIRECT page load for Transaksi
// Make sure the URL includes '/transaksis' or similar unique identifier
if (window.location.pathname.includes('/transaksis') && typeof $ !== 'undefined') {
    $(document).ready(function () {
        if (!$('body').data('transaksi-initialized-direct')) {
            console.log('Direct page load initialization for Transaksi.');
            initTransaksis();
            $('body').data('transaksi-initialized-direct', true);
        }
    });
}

// Handles content loading via AJAX using a specific event for Transaksi
$(document).on("transaksis:init", function (event) {
    console.log('Event transaksis:init received.');
    initTransaksis();
    fetchTransaksis(new URLSearchParams(window.location.search).get('page') || 1);
    $('body').data('transaksi-initialized-direct', false);
});

// --- Event Listeners for UI Interactions (Transaksi Specific IDs/Classes) ---

// Search Input: Trigger fetch on typing (debounced) - Assuming shared ID #search
$(document).on('input', '#search', function () {
    // Only act if the transaksi container is present on the page
    if ($('#transaksiTableContainer').length === 0) return;
    clearTimeout(debounceTimerTransaksi); // Use specific timer
    const currentSearchValue = $(this).val();
    debounceTimerTransaksi = setTimeout(() => {
        console.log('Debounced search triggered for Transaksi:', currentSearchValue);
        fetchTransaksis(1); // Fetch transaksi data
    }, DEBOUNCE_DELAY_TRANSAKSI);
});

// Search Input: Prevent Enter key form submission - Assuming shared ID #search
$(document).on('keydown', '#search', function (e) {
    // Only act if the transaksi container is present
    if ($('#transaksiTableContainer').length === 0) return;
    if (e.keyCode === 13) {
        e.preventDefault();
    }
});

// Sorting: Toggle Ascending/Descending Order Button
$(document).on('click', '#toggleSortOrderTransaksi', function () { // Use Transaksi ID
    sortOrderTransaksi = sortOrderTransaksi === 'asc' ? 'desc' : 'asc';
    // $('#sort-orderTransaksi').val(sortOrderTransaksi); // Optional hidden input
    $(this).find('svg').toggleClass('rotate-180');
    console.log('Transaksi Sort order toggled to:', sortOrderTransaksi);
    fetchTransaksis(1);
});

// Sorting: Select Sort By Field from Dropdown Menu
$(document).on('click', '#sortByPopupTransaksi .sort-optionTransaksi', function (e) { // Use Transaksi IDs/Classes
    e.preventDefault();
    const newSortBy = $(this).data('sortby');
    if (newSortBy && newSortBy !== sortByTransaksi) {
        sortByTransaksi = newSortBy;
        // $('#sort-byTransaksi').val(sortByTransaksi); // Optional hidden input
        $('#sortByButtonTransaksiText').text('Sort By: ' + $(this).text()); // Use Transaksi ID
        console.log('Transaksi Sort by changed to:', sortByTransaksi);
        fetchTransaksis(1);
    }
    $('#sortByPopupTransaksi').addClass('hidden'); // Use Transaksi ID
});

// Sorting: Show/Hide Sort By Dropdown
$(document).on('click', '#sortByButtonTransaksi', function (e) { // Use Transaksi ID
    e.stopPropagation();
    $('#sortByPopupTransaksi').toggleClass('hidden'); // Use Transaksi ID
});

// Sorting: Close Sort By Dropdown when clicking outside
$(document).on('click', function (e) {
    const sortByPopup = $('#sortByPopupTransaksi'); // Use Transaksi ID
    const sortByButton = $('#sortByButtonTransaksi'); // Use Transaksi ID
    if (!sortByPopup.hasClass('hidden') && !sortByButton.is(e.target) && sortByPopup.has(e.target).length === 0) {
        sortByPopup.addClass('hidden');
    }
});

// Filtering: Handle changes on any Transaksi filter dropdown
// Ensure IDs match your transaksi index blade file
$(document).on('change', '#filter_status_pembayaran, #filter_metode_pembayaran', function () {
    const filterType = $(this).attr('id');
    const filterValue = $(this).val();
    console.log(`Transaksi Filter changed - ${filterType}: ${filterValue}`);
    fetchTransaksis(1);
});

// Reset Filter Button - Assuming shared ID #resetFiltersButton
$(document).on('click', '#resetFiltersButton', function () {
    // Only act if the transaksi container is present
    if ($('#transaksiTableContainer').length === 0) return;
    console.log('Reset filters button clicked for Transaksi.');
    // Ensure IDs match your transaksi index blade file
    $('#filter_status_pembayaran').val('');
    $('#filter_metode_pembayaran').val('');
    fetchTransaksis(1);
});

// Pagination: Change Items Per Page Dropdown
$(document).on('change', '#perPageTransaksi', function () { // Use Transaksi ID
    perPageTransaksi = $(this).val();
    console.log('Transaksi Per page changed to:', perPageTransaksi);
    fetchTransaksis(1);
});

// Pagination: Handle Clicks on Page Number/Arrow Links
$(document).on('click', '#transaksiTableContainer nav[role="navigation"] a', function (e) { // Use Transaksi Container ID
    e.preventDefault();
    const href = $(this).attr('href');
    if (!href || href === '#' || $(this).parent().hasClass('disabled') || $(this).hasClass('disabled') || $(this).hasClass('cursor-not-allowed') || $(this).parent().hasClass('active')) {
        return;
    }
    try {
        const url = new URL(href);
        const page = url.searchParams.get('page');
        if (page && !isNaN(page)) {
            fetchTransaksis(page); // Fetch specific transaksi page
        } else {
            console.warn('Could not extract valid page number from Transaksi pagination link:', href);
        }
    } catch (error) {
        console.error("Error processing Transaksi pagination URL:", href, error);
    }
});

// --- Row Selection Logic (Assuming shared classes/IDs) ---

// Checkbox: Select/Deselect All Rows
$(document).on('change', '#checkbox-all', function () {
    if ($('#transaksiTableContainer').length === 0) return; // Target specific container context if needed
    const isChecked = $(this).prop('checked');
    $('#transaksiTableContainer .checkbox-row') // Target within container
        .prop('checked', isChecked)
        .closest('tr').toggleClass('bg-indigo-50', isChecked);
});

// Checkbox: Select/Deselect Single Row
$(document).on('change', '#transaksiTableContainer .checkbox-row', function () { // Target within container
    const $this = $(this);
    $this.closest('tr').toggleClass('bg-indigo-50', $this.prop('checked'));
    const totalCheckboxes = $('#transaksiTableContainer .checkbox-row').length;
    const checkedCheckboxes = $('#transaksiTableContainer .checkbox-row:checked').length;
    $('#checkbox-all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
});

// --- Delete Actions ---

// Delete: Single Row Button Click
$(document).on('click', '.delete-transaksi', function (e) { // Use specific class
    e.preventDefault();
    const $button = $(this);
    let idTransaksi = $button.data('id');
    let row = $button.closest('tr');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (!idTransaksi || !csrfToken) return;

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Hapus transaksi (ID: ${idTransaksi})? Data tidak dapat dikembalikan!`, // Update text
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteUrl = baseUrlTransaksi + '/single/' + idTransaksi; // Construct specific delete URL
            console.log("Attempting single transaksi delete:", deleteUrl);

            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                dataType: 'json',
                success: function (response) {
                    if (response && response.success) {
                        row.fadeOut(400, function () { $(this).remove(); });
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message || 'Transaksi berhasil dihapus.', timer: 1500, showConfirmButton: false, timerProgressBar: true }); // Update text
                        if ($('#transaksiTableContainer tbody tr').not(':has(td[colspan])').length <= 1) {
                            fetchTransaksis(1);
                        }
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus transaksi.', 'error'); // Update text
                    }
                },
                error: function (xhr, status, error) {
                    let errorMsg = 'Terjadi kesalahan saat menghapus transaksi.'; // Update text
                    if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                    else if (xhr.status === 404) errorMsg = 'Data transaksi tidak ditemukan (404).'; // Update text
                    else if (xhr.status === 403) errorMsg = 'Anda tidak diizinkan melakukan aksi ini (403).';
                    Swal.fire('Error', errorMsg, 'error');
                    console.error("Single transaksi delete error:", status, error, xhr.responseText);
                }
            });
        }
    });
});

// Delete: Bulk Action Button Click - Assuming shared ID #bulkDeleteButton
$(document).on('click', '#bulkDeleteButton', function (e) {
    // Only act if the transaksi container is present
    if ($('#transaksiTableContainer').length === 0) return;
    e.preventDefault();

    let selectedIds = $('#transaksiTableContainer .checkbox-row:checked').map(function () { // Target within container
        return $(this).val();
    }).get();

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (selectedIds.length === 0) {
        Swal.fire('Perhatian', 'Pilih setidaknya satu transaksi untuk dihapus.', 'warning'); // Update text
        return;
    }
    if (!csrfToken) {
        Swal.fire('Error', 'Kesalahan keamanan (CSRF). Refresh halaman.', 'error');
        return;
    }

    const bulkDeleteUrl = baseUrlTransaksi + '/mass-delete'; // URL for the bulk delete route

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus ${selectedIds.length} transaksi yang dipilih secara permanen!`, // Update text
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const requestData = {
                _token: csrfToken,
                _method: 'DELETE',
                ids: selectedIds
            };
            console.log("Attempting bulk transaksi delete:", bulkDeleteUrl, "Data:", requestData);

            $.ajax({
                url: bulkDeleteUrl,
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function (response) {
                    if (response && response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message || `${selectedIds.length} Transaksi berhasil dihapus.`, timer: 2000, showConfirmButton: false, timerProgressBar: true }); // Update text
                        fetchTransaksis(1);
                        $('#checkbox-all').prop('checked', false);
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus transaksi.', 'error'); // Update text
                    }
                },
                error: function (xhr, status, error) {
                    let errorMsg = 'Terjadi kesalahan saat menghapus transaksi.'; // Update text
                    if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                    else if (xhr.status === 403) errorMsg = 'Anda tidak diizinkan melakukan aksi ini (403).';
                    Swal.fire('Error', errorMsg, 'error');
                    console.error("Bulk transaksi delete error:", status, error, xhr.responseText);
                }
            });
        }
    });
});

// --- Browser History Navigation (Back/Forward Buttons) ---

window.addEventListener('popstate', function (event) {
    // Check if the current path is for transactions before acting
    if (window.location.pathname.includes('/transaksis')) { // Adjust path check if needed
        console.log('Popstate event triggered for Transaksi. Current URL:', window.location.href, 'State:', event.state);
        initTransaksis();
        fetchTransaksis(new URLSearchParams(window.location.search).get('page') || 1);
    }
});
