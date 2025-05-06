/**
 * pemesanan.js
 *
 * Handles dynamic interactions for the Admin Pemesanan (Orders) index page.
 * Features: AJAX table loading, sorting, pagination, search-as-you-type (debounced),
 * selection, single/bulk delete, and back/forward navigation support.
 */

// Global state variables
let sortBy = 'tanggal_pesan';
let sortOrder = 'desc';
let perPage = 10;
let baseUrl = ''; // Base URL for the pemesanan index route
let debounceTimer; // Timer for debouncing search input
const DEBOUNCE_DELAY = 400; // Delay in milliseconds for search debounce

/**
 * Initializes the state (sortBy, sortOrder, perPage, search term)
 * based on URL query parameters and updates corresponding UI elements.
 */
function initPemesanans() {
    // Determine the base URL (e.g., /pemesanan)
    baseUrl = window.location.pathname.replace(/\/$/, "");
    console.log('Pemesanan Initialized. Base URL set to:', baseUrl);

    // Read parameters from the current URL
    const queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || 'tanggal_pesan';
    sortOrder = queryParams.get('sortOrder') || 'desc';
    perPage = queryParams.get('perPage') || 10;
    const currentSearch = queryParams.get('search') || '';

    // Update hidden input values (if they exist)
    $('#sort-byPemesanan').val(sortBy); // Make sure ID matches index.blade.php
    $('#sort-order').val(sortOrder); // Make sure ID matches index.blade.php

    // Update visible UI elements
    $('#perPagePemesanan').val(perPage);
    $('#search').val(currentSearch);

    // Update Sort By button text
    const initialSortByText = $('#sortByPopupPemesanan .sort-optionPemesanan[data-sortby="' + sortBy + '"]').text();
    $('#sortByButtonPemesananText').text('Sort By: ' + (initialSortByText || 'Tgl Pesan'));

    // Update Sort Order button icon rotation
    $('#toggleSortOrderPemesanan svg').toggleClass('rotate-180', sortOrder !== 'asc');
}

/**
 * Updates the browser's URL query string using pushState
 * to reflect the current table state (search, sort, page, etc.)
 * without a full page reload. Enables back/forward navigation.
 * @param {number} page - The current page number.
 */
function updateUrl(page) {
    const query = new URLSearchParams({
        search: $('#search').val() || '',
        sortBy: sortBy,
        sortOrder: sortOrder,
        perPage: perPage,
        page: page
    }).toString();
    const newUrl = baseUrl + '?' + query;
    // Use pushState to create a history entry
    window.history.pushState({ path: newUrl }, '', newUrl);
    // console.log('URL updated to:', newUrl); // Optional: Keep for debugging if needed
}

/**
 * Fetches pemesanan data from the server via AJAX based on
 * current state variables (search, sort, pagination) and updates the table container.
 * @param {number} [page=1] - The page number to fetch.
 */
function fetchPemesanans(page = 1) {
    const searchVal = $('#search').val() || '';
    console.log("Fetching page:", page, "| SortBy:", sortBy, "| SortOrder:", sortOrder, "| PerPage:", perPage, "| Search:", searchVal, "| URL:", baseUrl);

    const requestData = {
        search: searchVal,
        sortBy: sortBy,
        sortOrder: sortOrder,
        perPage: perPage,
        page: page
    };

    if (!baseUrl) {
        console.error("Base URL is not set. Cannot fetch data.");
        $('#pemesananTableContainer').html('<div class="text-center p-5 text-red-500">Kesalahan: URL dasar tidak diatur.</div>');
        return;
    }

    const container = $('#pemesananTableContainer');

    $.ajax({
        url: baseUrl, // Use the base URL determined in init
        type: 'GET',
        dataType: 'json',
        data: requestData,
        beforeSend: function () {
            // Show loading overlay (prevent multiple overlays)
            if ($('#loadingOverlayPemesanan').length > 0) return;
            container.css({ 'position': 'relative', 'min-height': '250px' }); // Ensure container is relative and has min height
            const loadingHtml = `
                <div id="loadingOverlayPemesanan" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-20 rounded-lg" aria-label="Loading...">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>`;
            container.append(loadingHtml);
            // console.log('Loading overlay shown.'); // Optional
        },
        success: function (response) {
            $('#loadingOverlayPemesanan').remove(); // Remove overlay on success
            if (response && response.html) {
                container.html(response.html); // Replace table content
                // Update URL only if it's not a popstate triggered fetch
                if (!event || event.type !== 'popstate') {
                    updateUrl(page); // Update URL to reflect the loaded state
                }
                console.log('Table updated successfully.');
            } else {
                container.html('<div class="text-center p-5 text-red-500">Gagal memuat data atau format respon salah.</div>');
                console.error("Invalid response structure:", response);
            }
        },
        error: function (xhr, status, error) {
            $('#loadingOverlayPemesanan').remove(); // Remove overlay on error
            container.html('<div class="text-center p-5 text-red-500">Terjadi kesalahan saat memuat data. Silakan coba lagi.</div>');
            console.error("Error fetching Pemesanan data:", status, error, xhr.responseText);
        }
    });
}

// --- Document Ready and Initialization ---

// Handles DIRECT page load (e.g., typing /pemesanan in URL bar)
if (window.location.pathname.includes('/pemesanan') && typeof $ !== 'undefined') {
    $(document).ready(function () {
        // Use flag to prevent double execution ONLY on direct load
        if (!$('body').data('pemesanan-initialized-direct')) {
            console.log('Direct page load initialization for Pemesanan.');
            initPemesanans(); // Set initial state from URL
            fetchPemesanans(new URLSearchParams(window.location.search).get('page') || 1); // Fetch initial data
            $('body').data('pemesanan-initialized-direct', true); // Mark as initialized for direct load
        }
    });
}

// Handles content loading via AJAX (e.g., sidebar navigation)
$(document).on("pemesanans:init", function () {
    console.log('Event pemesanans:init received. Initializing/Re-initializing...');
    // ** REMOVED THE FLAG CHECK HERE **
    // ALWAYS initialize UI and fetch data when loaded via sidebar AJAX
    initPemesanans();
    fetchPemesanans(new URLSearchParams(window.location.search).get('page') || 1);
    // Reset the direct load flag in case the user navigates away and back via AJAX
    $('body').data('pemesanan-initialized-direct', false);
});


// --- Event Listeners ---

// Search: Handle typing with Debouncing
$(document).on('input', '#search', function () {
    clearTimeout(debounceTimer); // Reset debounce timer on each key press
    const currentSearchValue = $(this).val(); // Get the current value

    debounceTimer = setTimeout(() => {
        console.log('Debounced search triggered for value:', currentSearchValue);
        fetchPemesanans(1); // Fetch results for page 1 based on the search term
    }, DEBOUNCE_DELAY);
});

// Search: Prevent Enter key from submitting the surrounding form
$(document).on('keydown', '#search', function (e) {
    if (e.keyCode === 13) { // 13 = Enter key
        console.log('Enter key detected in search input, preventing default.');
        e.preventDefault(); // Stop the default action (likely form submission)
    }
});

// Sorting: Toggle Asc/Desc Order
$(document).on('click', '#toggleSortOrderPemesanan', function () {
    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
    $('#sort-order').val(sortOrder);
    $(this).find('svg').toggleClass('rotate-180');
    console.log('Sort order toggled to:', sortOrder);
    fetchPemesanans(1);
});

// Sorting: Select Sort By Field from Dropdown
$(document).on('click', '#sortByPopupPemesanan .sort-optionPemesanan', function (e) {
    e.preventDefault();
    const newSortBy = $(this).data('sortby');
    if (newSortBy && newSortBy !== sortBy) {
        sortBy = newSortBy;
        $('#sort-byPemesanan').val(sortBy);
        $('#sortByButtonPemesananText').text('Sort By: ' + $(this).text());
        console.log('Sort by changed to:', sortBy);
        fetchPemesanans(1);
    }
    $('#sortByPopupPemesanan').addClass('hidden');
});

// Sorting: Toggle Sort By Dropdown Visibility
$(document).on('click', '#sortByButtonPemesanan', function (e) {
    e.stopPropagation();
    $('#sortByPopupPemesanan').toggleClass('hidden');
});

// Sorting: Close Sort By Dropdown when clicking outside
$(document).on('click', function (e) {
    if (!$('#sortByPopupPemesanan').hasClass('hidden') && !$(e.target).closest('#sortByButtonPemesanan, #sortByPopupPemesanan').length) {
        $('#sortByPopupPemesanan').addClass('hidden');
    }
});

// Pagination: Change Items Per Page
$(document).on('change', '#perPagePemesanan', function () {
    perPage = $(this).val();
    console.log('Per page changed to:', perPage);
    fetchPemesanans(1);
});

// Pagination: Handle Clicks on Page Links
$(document).on('click', '#pemesananTableContainer nav[role="navigation"] a', function (e) {
    e.preventDefault();
    const href = $(this).attr('href');
    if (!href || href === '#' || $(this).parent().hasClass('disabled') || $(this).hasClass('disabled') || $(this).hasClass('cursor-not-allowed') || $(this).parent().hasClass('active')) {
        return;
    }
    console.log('Pagination link clicked:', href);
    try {
        const url = new URL(href);
        const page = url.searchParams.get('page');
        if (page && !isNaN(page)) {
            fetchPemesanans(page);
        } else {
            console.warn('Could not extract valid page number from pagination link:', href);
        }
    } catch (error) {
        console.error("Error processing pagination URL:", href, error);
    }
});

// --- Row Selection Logic ---

// Select/Deselect All Rows
$(document).on('change', '#checkbox-all', function () {
    const isChecked = $(this).prop('checked');
    $('#pemesananTableContainer .checkbox-row')
        .prop('checked', isChecked)
        .closest('tr').toggleClass('bg-indigo-50', isChecked);
});

// Select/Deselect a Single Row
$(document).on('change', '#pemesananTableContainer .checkbox-row', function () {
    const $this = $(this);
    $this.closest('tr').toggleClass('bg-indigo-50', $this.prop('checked'));
    const totalCheckboxes = $('#pemesananTableContainer .checkbox-row').length;
    const checkedCheckboxes = $('#pemesananTableContainer .checkbox-row:checked').length;
    $('#checkbox-all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
});

// --- Delete Logic ---

// Single Row Delete
$(document).on('click', '.delete-pemesanan', function (e) {
    e.preventDefault();
    const $button = $(this);
    let idPemesanan = $button.data('id');
    let row = $button.closest('tr');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (!idPemesanan || !csrfToken) { /* Validation */ return; }

    Swal.fire({ /* Confirmation */
        title: 'Apakah Anda yakin?', text: `Hapus pemesanan (ID: ${idPemesanan})?`, icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!', cancelButtonText: 'Batal', reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteUrl = baseUrl + '/single/' + idPemesanan;
            console.log("Attempting single delete:", deleteUrl);
            $.ajax({
                url: deleteUrl, type: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken }, dataType: 'json',
                success: function (response) {
                    if (response && response.success) {
                        row.fadeOut(400, function () { $(this).remove(); });
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message || 'Pemesanan dihapus.', timer: 1500, showConfirmButton: false, timerProgressBar: true });
                        if ($('#pemesananTableContainer tbody tr').not(':has(td[colspan])').length <= 1) { fetchPemesanans(1); }
                    } else { Swal.fire('Gagal', response.message || 'Gagal menghapus.', 'error'); }
                },
                error: function (xhr, status, error) { /* Error handling */
                    let errorMsg = 'Terjadi kesalahan.'; if (xhr.responseJSON?.message) errorMsg = xhr.responseJSON.message; else if (xhr.status === 404) errorMsg = 'Data tidak ditemukan.'; else if (xhr.status === 403) errorMsg = 'Aksi tidak diizinkan.'; Swal.fire('Error', errorMsg, 'error'); console.error("Single delete error:", error);
                }
            });
        }
    });
});

// Bulk Delete (Multiple Rows)
$(document).on('click', '#bulkDeleteButton', function (e) {
    e.preventDefault();
    let selectedIds = $('#pemesananTableContainer .checkbox-row:checked').map(function () { return $(this).val(); }).get();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (selectedIds.length === 0 || !csrfToken) { /* Validation */ return; }

    const bulkDeleteUrl = baseUrl + '/mass-delete';

    Swal.fire({ /* Confirmation */
        title: 'Apakah Anda yakin?', text: `Hapus ${selectedIds.length} pemesanan?`, icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!', cancelButtonText: 'Batal', reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const requestData = { _token: csrfToken, _method: 'DELETE', ids: selectedIds };
            console.log("Attempting bulk delete:", bulkDeleteUrl, "Data:", requestData);
            $.ajax({
                url: bulkDeleteUrl, type: 'POST', data: requestData, dataType: 'json',
                success: function (response) {
                    if (response && response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message || 'Pemesanan dihapus.', timer: 2000, showConfirmButton: false, timerProgressBar: true });
                        fetchPemesanans(1);
                        $('#checkbox-all').prop('checked', false);
                    } else { Swal.fire('Gagal', response.message || 'Gagal menghapus.', 'error'); }
                },
                error: function (xhr, status, error) { /* Error handling */
                    let errorMsg = 'Terjadi kesalahan.'; if (xhr.responseJSON?.message) errorMsg = xhr.responseJSON.message; else if (xhr.status === 403) errorMsg = 'Aksi tidak diizinkan.'; Swal.fire('Error', errorMsg, 'error'); console.error("Bulk delete error:", error);
                }
            });
        }
    });
});

// --- Browser History Navigation (Back/Forward Buttons) ---

window.addEventListener('popstate', function (event) {
    console.log('Popstate event triggered. Current URL:', window.location.href, 'State:', event.state);
    // Re-initialize UI state from the potentially changed URL and fetch data
    // No need for flag check here, popstate means navigation happened.
    initPemesanans();
    fetchPemesanans(new URLSearchParams(window.location.search).get('page') || 1);
});
