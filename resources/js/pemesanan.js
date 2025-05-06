/**
 * pemesanan.js
 *
 * Handles dynamic interactions for the Admin Pemesanan (Orders) index page.
 * Features: AJAX table loading, sorting, pagination, search-as-you-type (debounced),
 * filtering, selection, single/bulk delete, and back/forward navigation support.
 */

// --- Global State Variables ---
let sortBy = 'tanggal_pesan';    // Default sort column
let sortOrder = 'desc';           // Default sort direction
let perPage = 10;                 // Default items per page
let baseUrl = '';                 // Base URL for the pemesanan index route (determined on init)
let debounceTimer;                // Timer ID for debouncing search input
const DEBOUNCE_DELAY = 400;       // Delay in milliseconds for search debounce

// --- Initialization Functions ---

/**
 * Initializes the state (sortBy, sortOrder, perPage, search, filters)
 * based on URL query parameters and updates corresponding UI elements.
 */
function initPemesanans() {
    // Determine the base URL (e.g., /pemesanan) from the current window location
    baseUrl = window.location.pathname.replace(/\/$/, "");
    console.log('Pemesanan Initialized. Base URL set to:', baseUrl);

    // Read parameters from the current URL's query string
    const queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || 'tanggal_pesan';
    sortOrder = queryParams.get('sortOrder') || 'desc';
    perPage = queryParams.get('perPage') || 10;
    const currentSearch = queryParams.get('search') || '';
    const filterMetode = queryParams.get('filter_metode') || '';
    const filterLayanan = queryParams.get('filter_layanan') || '';
    const filterStatus = queryParams.get('filter_status') || '';

    // Update hidden input values used to store state (ensure IDs match HTML)
    $('#sort-byPemesanan').val(sortBy);
    $('#sort-order').val(sortOrder);

    // Update visible UI elements to match the current state
    $('#perPagePemesanan').val(perPage);
    $('#search').val(currentSearch);
    $('#filter_metode').val(filterMetode);
    $('#filter_layanan').val(filterLayanan);
    $('#filter_status').val(filterStatus);

    // Update the "Sort By" button text
    const initialSortByText = $('#sortByPopupPemesanan .sort-optionPemesanan[data-sortby="' + sortBy + '"]').text();
    $('#sortByButtonPemesananText').text('Sort By: ' + (initialSortByText || 'Tgl Pesan')); // Default text

    // Update the Sort Order button's icon rotation
    $('#toggleSortOrderPemesanan svg').toggleClass('rotate-180', sortOrder !== 'asc');
}

// --- Core AJAX and URL Functions ---

/**
 * Updates the browser's URL query string using history.pushState.
 * This reflects the current table state (search, sort, filters, page)
 * and allows for back/forward navigation without full page reloads.
 * @param {number} page - The current page number being displayed.
 */
function updateUrl(page) {
    const query = new URLSearchParams({
        search: $('#search').val() || '',
        sortBy: sortBy,
        sortOrder: sortOrder,
        perPage: perPage,
        page: page,
        filter_metode: $('#filter_metode').val() || '',
        filter_layanan: $('#filter_layanan').val() || '',
        filter_status: $('#filter_status').val() || ''
    }).toString();
    const newUrl = baseUrl + '?' + query;

    // Use pushState to add a new entry to the browser's history
    window.history.pushState({ path: newUrl }, '', newUrl);
    // console.log('URL updated to:', newUrl); // Optional: for debugging
}

/**
 * Fetches pemesanan data from the server via an AJAX GET request.
 * Sends current state (search, sort, filters, pagination) as query parameters.
 * Updates the '#pemesananTableContainer' with the returned HTML table partial.
 * @param {number} [page=1] - The page number to fetch.
 */
function fetchPemesanans(page = 1) {
    const searchVal = $('#search').val() || '';
    const filterMetodeVal = $('#filter_metode').val() || '';
    const filterLayananVal = $('#filter_layanan').val() || '';
    const filterStatusVal = $('#filter_status').val() || '';

    // Log the parameters being sent for debugging
    console.log(`Fetching page: ${page} | Sort: ${sortBy} ${sortOrder} | PerPage: ${perPage} | Search: ${searchVal} | F_Metode: ${filterMetodeVal} | F_Layanan: ${filterLayananVal} | F_Status: ${filterStatusVal} | URL: ${baseUrl}`);

    // Data object for the AJAX request
    const requestData = {
        search: searchVal,
        sortBy: sortBy,
        sortOrder: sortOrder,
        perPage: perPage,
        page: page,
        filter_metode: filterMetodeVal,
        filter_layanan: filterLayananVal,
        filter_status: filterStatusVal
    };

    // Ensure the base URL has been set
    if (!baseUrl) {
        console.error("Base URL is not set. Cannot fetch data.");
        $('#pemesananTableContainer').html('<div class="text-center p-5 text-red-500">Kesalahan: URL dasar tidak diatur.</div>');
        return;
    }

    const container = $('#pemesananTableContainer');

    $.ajax({
        url: baseUrl, // Target the base route (e.g., /pemesanan)
        type: 'GET',
        dataType: 'json', // Expect a JSON response from the controller
        data: requestData, // Send state as query parameters
        beforeSend: function () {
            // Display a loading indicator (ensure only one overlay exists)
            if ($('#loadingOverlayPemesanan').length > 0) return;
            container.css({ 'position': 'relative', 'min-height': '250px' });
            const loadingHtml = `
                <div id="loadingOverlayPemesanan" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-20 rounded-lg" aria-label="Loading...">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>`;
            container.append(loadingHtml);
            // console.log('Loading overlay shown.'); // Optional debug
        },
        success: function (response) {
            $('#loadingOverlayPemesanan').remove(); // Remove loader
            if (response && response.html) {
                container.html(response.html); // Update table content
                // Update the URL unless this fetch was triggered by popstate
                const isPopState = typeof event !== 'undefined' && event instanceof PopStateEvent;
                if (!isPopState) {
                    updateUrl(page);
                }
                console.log('Table updated successfully.');
            } else {
                // Handle cases where response is invalid or missing HTML
                container.html('<div class="text-center p-5 text-red-500">Gagal memuat data atau format respon salah.</div>');
                console.error("Invalid response structure:", response);
            }
        },
        error: function (xhr, status, error) {
            $('#loadingOverlayPemesanan').remove(); // Remove loader
            // Display user-friendly error message
            container.html('<div class="text-center p-5 text-red-500">Terjadi kesalahan saat memuat data. Silakan coba lagi.</div>');
            console.error("Error fetching Pemesanan data:", status, error, xhr.responseText);
        }
    });
}

// --- Page Load and Initialization ---

// Handles DIRECT page load (e.g., browser refresh or typing URL)
if (window.location.pathname.includes('/pemesanan') && typeof $ !== 'undefined') {
    $(document).ready(function () {
        // Use a flag specific to direct load to prevent double init if script runs twice
        if (!$('body').data('pemesanan-initialized-direct')) {
            console.log('Direct page load initialization.');
            initPemesanans(); // Initialize state from URL
            // NOTE: No initial fetch here assuming the Blade view includes the first table render
            $('body').data('pemesanan-initialized-direct', true); // Mark as initialized
        }
    });
}

// Handles content loading via AJAX (e.g., sidebar navigation triggering an event)
// Ensure the event name "pemesanans:init" matches what your sidebar script triggers.
$(document).on("pemesanans:init", function (event) {
    console.log('Event pemesanans:init received.');
    initPemesanans(); // Always initialize UI state from URL when loaded via AJAX
    // Fetch data to ensure the table content matches the current (potentially new) URL state
    fetchPemesanans(new URLSearchParams(window.location.search).get('page') || 1);
    // Reset the direct load flag in case user navigates away and back via AJAX
    $('body').data('pemesanan-initialized-direct', false);
});

// --- Event Listeners for UI Interactions ---

// Search Input: Trigger fetch on typing (debounced)
$(document).on('input', '#search', function () {
    clearTimeout(debounceTimer); // Clear previous timeout
    const currentSearchValue = $(this).val(); // Get value now
    debounceTimer = setTimeout(() => {
        console.log('Debounced search triggered for:', currentSearchValue);
        fetchPemesanans(1); // Fetch page 1 with the new search term
    }, DEBOUNCE_DELAY);
});

// Search Input: Prevent Enter key from causing form submission
$(document).on('keydown', '#search', function (e) {
    if (e.keyCode === 13) { // 13 = Enter key code
        console.log('Enter key pressed in search, preventing default.');
        e.preventDefault(); // Stop default browser action for Enter
    }
});

// Sorting: Toggle Ascending/Descending Order Button
$(document).on('click', '#toggleSortOrderPemesanan', function () {
    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'; // Toggle state
    $('#sort-order').val(sortOrder); // Update hidden input (optional)
    $(this).find('svg').toggleClass('rotate-180'); // Toggle icon
    console.log('Sort order toggled to:', sortOrder);
    fetchPemesanans(1); // Refetch page 1 with new sort order
});

// Sorting: Select Sort By Field from Dropdown Menu
$(document).on('click', '#sortByPopupPemesanan .sort-optionPemesanan', function (e) {
    e.preventDefault();
    const newSortBy = $(this).data('sortby');
    if (newSortBy && newSortBy !== sortBy) { // If value changed
        sortBy = newSortBy; // Update state
        $('#sort-byPemesanan').val(sortBy); // Update hidden input (optional)
        $('#sortByButtonPemesananText').text('Sort By: ' + $(this).text()); // Update button text
        console.log('Sort by changed to:', sortBy);
        fetchPemesanans(1); // Refetch page 1 with new sort field
    }
    $('#sortByPopupPemesanan').addClass('hidden'); // Close the dropdown
});

// Sorting: Show/Hide Sort By Dropdown
$(document).on('click', '#sortByButtonPemesanan', function (e) {
    e.stopPropagation(); // Prevent click from immediately closing dropdown via document listener
    $('#sortByPopupPemesanan').toggleClass('hidden');
});

// Sorting: Close Sort By Dropdown when clicking outside of it
$(document).on('click', function (e) {
    const sortByPopup = $('#sortByPopupPemesanan');
    const sortByButton = $('#sortByButtonPemesanan');
    // If the popup is visible and the click target is not the popup or the button
    if (!sortByPopup.hasClass('hidden') && !sortByButton.is(e.target) && sortByPopup.has(e.target).length === 0) {
        sortByPopup.addClass('hidden');
    }
});

// Filtering: Handle changes on any filter dropdown
$(document).on('change', '#filter_metode, #filter_layanan, #filter_status', function () {
    const filterType = $(this).attr('id'); // e.g., 'filter_metode'
    const filterValue = $(this).val();
    console.log(`Filter changed - ${filterType}: ${filterValue}`);
    fetchPemesanans(1); // Refetch data from page 1 applying the new filter(s)
});

// **** NEW: Reset Filter Button ****
$(document).on('click', '#resetFiltersButton', function () {
    console.log('Reset filters button clicked.');
    // Clear the dropdowns
    $('#filter_metode').val('');
    $('#filter_layanan').val('');
    $('#filter_status').val('');
    // Refetch data with cleared filters (will pick up current sort/search)
    fetchPemesanans(1);
});

// Pagination: Change Items Per Page Dropdown
$(document).on('change', '#perPagePemesanan', function () {
    perPage = $(this).val(); // Update state
    console.log('Per page changed to:', perPage);
    fetchPemesanans(1); // Refetch page 1 with new item count
});

// Pagination: Handle Clicks on Page Number/Arrow Links
$(document).on('click', '#pemesananTableContainer nav[role="navigation"] a', function (e) {
    e.preventDefault(); // Prevent default link navigation
    const href = $(this).attr('href');

    // Basic validation for the link (ignore if no href, disabled, or current page)
    if (!href || href === '#' || $(this).parent().hasClass('disabled') || $(this).hasClass('disabled') || $(this).hasClass('cursor-not-allowed') || $(this).parent().hasClass('active')) {
        console.log('Pagination click ignored (invalid/disabled/active).');
        return;
    }

    console.log('Pagination link clicked:', href);
    try {
        const url = new URL(href); // Parse the link URL
        const page = url.searchParams.get('page'); // Extract the target page number
        if (page && !isNaN(page)) { // If page is valid
            fetchPemesanans(page); // Fetch the specific page
        } else {
            console.warn('Could not extract valid page number from pagination link:', href);
        }
    } catch (error) {
        console.error("Error processing pagination URL:", href, error);
    }
});

// --- Row Selection Logic ---

// Checkbox: Select/Deselect All Rows in the header
$(document).on('change', '#checkbox-all', function () {
    const isChecked = $(this).prop('checked');
    // Target checkboxes only within the specific table container
    $('#pemesananTableContainer .checkbox-row')
        .prop('checked', isChecked)
        .closest('tr').toggleClass('bg-indigo-50', isChecked); // Add/remove highlight class
    console.log('Checkbox all toggled:', isChecked);
});

// Checkbox: Select/Deselect a Single Row
$(document).on('change', '#pemesananTableContainer .checkbox-row', function () {
    const $this = $(this);
    $this.closest('tr').toggleClass('bg-indigo-50', $this.prop('checked')); // Toggle highlight for this row

    // Update the header checkbox state based on whether all rows are now checked
    const totalCheckboxes = $('#pemesananTableContainer .checkbox-row').length;
    const checkedCheckboxes = $('#pemesananTableContainer .checkbox-row:checked').length;
    $('#checkbox-all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
});

// --- Delete Actions ---

// Delete: Single Row Button Click
$(document).on('click', '.delete-pemesanan', function (e) {
    e.preventDefault();
    const $button = $(this);
    let idPemesanan = $button.data('id'); // Get ID from data-id attribute
    let row = $button.closest('tr'); // Get the table row to remove later
    const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

    // Basic input validation
    if (!idPemesanan) { /* Handle missing ID */ return; }
    if (!csrfToken) { /* Handle missing CSRF */ return; }

    // Confirmation dialog using SweetAlert2
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Hapus pemesanan (ID: ${idPemesanan})? Data tidak dapat dikembalikan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Red confirm button
        cancelButtonColor: '#6b7280', // Gray cancel button
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!', // FontAwesome icon example
        cancelButtonText: 'Batal',
        reverseButtons: true // Puts confirm button on the right
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with deletion if confirmed
            const deleteUrl = baseUrl + '/single/' + idPemesanan; // Construct specific delete route URL
            console.log("Attempting single delete:", deleteUrl);

            $.ajax({
                url: deleteUrl,
                type: 'DELETE', // Use DELETE HTTP method
                headers: { 'X-CSRF-TOKEN': csrfToken }, // Send CSRF token in headers
                dataType: 'json', // Expect JSON response
                success: function (response) {
                    if (response && response.success) {
                        // Remove row from table with fade effect
                        row.fadeOut(400, function () { $(this).remove(); });
                        // Show success notification
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message || 'Pemesanan berhasil dihapus.', timer: 1500, showConfirmButton: false, timerProgressBar: true });
                        // Optional: Refresh if table becomes empty
                        if ($('#pemesananTableContainer tbody tr').not(':has(td[colspan])').length <= 1) {
                            fetchPemesanans(1); // Or fetch the current page
                        }
                    } else {
                        // Show failure message from server or generic error
                        Swal.fire('Gagal', response.message || 'Gagal menghapus pemesanan.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    // Handle AJAX errors (network, server error, etc.)
                    let errorMsg = 'Terjadi kesalahan saat menghapus.';
                    if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                    else if (xhr.status === 404) errorMsg = 'Data pemesanan tidak ditemukan (404).';
                    else if (xhr.status === 403) errorMsg = 'Anda tidak diizinkan melakukan aksi ini (403).';
                    Swal.fire('Error', errorMsg, 'error');
                    console.error("Single delete error:", status, error, xhr.responseText);
                }
            });
        }
    });
});

// Delete: Bulk Action Button Click
$(document).on('click', '#bulkDeleteButton', function (e) {
    e.preventDefault();
    // Get array of IDs from checked checkboxes within the table container
    let selectedIds = $('#pemesananTableContainer .checkbox-row:checked').map(function () {
        return $(this).val();
    }).get();

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Validations
    if (selectedIds.length === 0) {
        Swal.fire('Perhatian', 'Pilih setidaknya satu pemesanan untuk dihapus.', 'warning');
        return;
    }
    if (!csrfToken) {
        Swal.fire('Error', 'Kesalahan keamanan (CSRF). Refresh halaman.', 'error');
        return;
    }

    const bulkDeleteUrl = baseUrl + '/mass-delete'; // URL for the bulk delete route

    // Confirmation Dialog
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus ${selectedIds.length} pemesanan yang dipilih secara permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Data to be sent in the request body
            const requestData = {
                _token: csrfToken,
                _method: 'DELETE', // Method spoofing for Laravel DELETE route via POST
                ids: selectedIds   // Array of selected IDs
            };
            console.log("Attempting bulk delete:", bulkDeleteUrl, "Data:", requestData);

            $.ajax({
                url: bulkDeleteUrl,
                type: 'POST', // Use POST because HTML forms don't natively support DELETE
                data: requestData, // Send data in the request body
                dataType: 'json',
                success: function (response) {
                    if (response && response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message || `${selectedIds.length} Pemesanan berhasil dihapus.`, timer: 2000, showConfirmButton: false, timerProgressBar: true });
                        fetchPemesanans(1); // Refresh table, go back to page 1
                        $('#checkbox-all').prop('checked', false); // Uncheck the header checkbox
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus pemesanan.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    // Handle AJAX errors
                    let errorMsg = 'Terjadi kesalahan saat menghapus.';
                    if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                    else if (xhr.status === 403) errorMsg = 'Anda tidak diizinkan melakukan aksi ini (403).';
                    Swal.fire('Error', errorMsg, 'error');
                    console.error("Bulk delete error:", status, error, xhr.responseText);
                }
            });
        }
    });
});

// --- Browser History Navigation (Back/Forward Buttons) ---

// Listen for the 'popstate' event, which fires when the active history entry changes
window.addEventListener('popstate', function (event) {
    console.log('Popstate event triggered. Current URL:', window.location.href, 'State:', event.state);
    // The URL has changed due to back/forward navigation.
    // Re-initialize the UI state based on the new URL parameters.
    initPemesanans();
    // Fetch the data corresponding to the state in the new URL.
    fetchPemesanans(new URLSearchParams(window.location.search).get('page') || 1);
});
