/**
 * pemesanan.js
 *
 * Handles dynamic interactions for the Admin Pemesanan (Orders) index page.
 * Features: AJAX table loading, sorting, pagination, search, filtering,
 * selection, single/bulk delete, inline status update, and Quick View Modal.
 */

// --- Global State Variables ---
let sortBy = 'tanggal_pesan';
let sortOrder = 'desc';
let perPage = 10;
let baseUrl = '';
let debounceTimer;
const DEBOUNCE_DELAY = 400;

// --- Flowbite Modal Instance ---
let quickViewModalInstance = null;

/**
 * Initializes essential page elements, states, and the Quick View Modal.
 * Sets up base URL, UI states from URL params, and the Flowbite modal instance
 * including event listeners for its static close button.
 */
function initializePemesananPage() {
    console.log('Initializing Pemesanan Page...');

    // 1. Determine Base URL
    if (window.PEMESANAN_BASE_URL) {
        baseUrl = window.PEMESANAN_BASE_URL;
    } else if (window.location.pathname.includes('/pemesanan')) {
        baseUrl = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/pemesanan') + '/pemesanan'.length).replace(/\/index$/, '').replace(/\/$/, "");
    } else {
        baseUrl = '/pemesanan';
        console.warn("PEMESANAN_BASE_URL not found or path inference failed. Using default:", baseUrl);
    }
    console.log('Base URL set to:', baseUrl);

    // 2. Initialize UI states from URL parameters
    const queryParams = new URLSearchParams(window.location.search);
    sortBy = queryParams.get('sortBy') || 'tanggal_pesan';
    sortOrder = queryParams.get('sortOrder') || 'desc';
    perPage = queryParams.get('perPage') || 10;

    $('#sort-byPemesanan').val(sortBy);
    $('#sort-order').val(sortOrder);
    $('#perPagePemesanan').val(perPage);
    $('#search').val(queryParams.get('search') || '');
    $('#filter_metode').val(queryParams.get('filter_metode') || '');
    $('#filter_layanan').val(queryParams.get('filter_layanan') || '');
    $('#filter_status').val(queryParams.get('filter_status') || '');
    $('#filter_status_bayar').val(queryParams.get('filter_status_bayar') || '');

    const initialSortByTextEl = $('#sortByPopupPemesanan .sort-optionPemesanan[data-sortby="' + sortBy + '"]');
    const initialSortByText = initialSortByTextEl.length ? initialSortByTextEl.text() : 'Tgl Pesan';
    $('#sortByButtonPemesananText').text('Sort By: ' + initialSortByText);
    $('#toggleSortOrderPemesanan svg').toggleClass('rotate-180', sortOrder === 'desc');

    // 3. Initialize Flowbite Quick View Modal
    const modalElement = document.getElementById('quickViewModal');
    if (modalElement) {
        if (typeof Modal !== 'undefined') {
            const modalOptions = {
                placement: 'center-center',
                backdrop: 'static',
                closable: true, // Allows ESC key to close
            };
            quickViewModalInstance = new Modal(modalElement, modalOptions);
            console.log('Flowbite Quick View Modal instance initialized.');

            // Attach listener to static close button ('X' in modal header)
            const staticCloseButton = document.getElementById('staticCloseQuickViewModalButton');
            if (staticCloseButton) {
                staticCloseButton.addEventListener('click', () => {
                    if (quickViewModalInstance) {
                        quickViewModalInstance.hide();
                    }
                });
            }
        } else {
            console.error("Flowbite 'Modal' class not found. Ensure Flowbite is installed and bundled.");
        }
    } else {
        console.warn("#quickViewModal element not found. Cannot initialize modal.");
    }
    $('body').data('pemesanan-initialized', true);
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
        page: page || 1,
        filter_metode: $('#filter_metode').val() || '',
        filter_layanan: $('#filter_layanan').val() || '',
        filter_status: $('#filter_status').val() || '',
        filter_status_bayar: $('#filter_status_bayar').val() || ''
    });

    const pathOnlyBaseUrl = baseUrl.split('?')[0];
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
        url: baseUrl,
        type: 'GET',
        dataType: 'json',
        data: requestData,
        beforeSend: function () {
            if ($('#loadingOverlayPemesanan').length === 0) {
                container.css({ 'position': 'relative', 'min-height': '250px' });
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
            $('#loadingOverlayPesanan').remove(); // Pastikan ID konsisten atau perbaiki
            if (response && response.html) {
                container.html(response.html);
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
            container.html('<div class="text-center p-5 text-red-500">Terjadi kesalahan saat memuat data. Detail: ' + xhr.status + ' ' + error + '</div>');
            console.error("Error fetching Pemesanan data:", xhr, status, error);
        }
    });
}

// --- Event Handlers ---

// Page Load
$(document).ready(function () {
    if ($('#pemesananTableContainer').length > 0 && !$('body').data('pemesanan-initialized')) {
        initializePemesananPage();
    }
});

// Dynamic Content Initialization (e.g., after sidebar AJAX load)
$(document).on("pemesanans:init", function () {
    console.log('Event pemesanans:init received.');
    if ($('#pemesananTableContainer').length > 0) {
        initializePemesananPage(); // Re-initialize modal and other states
        const queryParams = new URLSearchParams(window.location.search);
        fetchPemesanans(queryParams.get('page') || 1);
    }
});

// Search Input (Debounced)
$(document).on('input', '#search', function () {
    if (!$('#pemesananTableContainer').length) return;
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => { fetchPemesanans(1); }, DEBOUNCE_DELAY);
});
$(document).on('submit', '#searchFormPemesanan', function (e) {
    e.preventDefault();
});

// Sorting Dropdown Toggle
$(document).on('click', '#sortByButtonPemesanan', function (e) {
    e.stopPropagation();
    $('#sortByPopupPemesanan').toggleClass('hidden');
});

// Sort By Option Click
$(document).on('click', '.sort-optionPemesanan', function (e) {
    e.preventDefault();
    const newSortBy = $(this).data('sortby');
    if (newSortBy && newSortBy !== sortBy) {
        sortBy = newSortBy;
        $('#sortByButtonPemesananText').text('Sort By: ' + $(this).text());
        fetchPemesanans(1);
    }
    $('#sortByPopupPemesanan').addClass('hidden');
});

// Sort Order Toggle
$(document).on('click', '#toggleSortOrderPemesanan', function () {
    sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
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
    $('#filter_metode, #filter_layanan, #filter_status, #filter_status_bayar, #search').val('');
    fetchPemesanans(1);
});

// Per Page Change
$(document).on('change', '#perPagePemesanan', function () {
    perPage = $(this).val();
    fetchPemesanans(1);
});

// Pagination Links Click
$(document).on('click', '#pemesananTableContainer .pagination a', function (e) {
    e.preventDefault();
    const href = $(this).attr('href');
    if (!href || href === '#' || $(this).parent().hasClass('disabled') || $(this).parent().hasClass('active')) return;
    try {
        const url = new URL(href);
        const page = url.searchParams.get('page');
        if (page && !isNaN(page)) {
            fetchPemesanans(page);
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
    const total = $('#pemesananTableContainer .checkbox-row').length;
    const checked = $('#pemesananTableContainer .checkbox-row:checked').length;
    $('#checkbox-all').prop('checked', total > 0 && total === checked);
});

/**
 * Handles opening the Quick View modal and loading its content via AJAX.
 * Attaches event listener to the dynamically loaded close button within the modal.
 */
$(document).on('click', '.quick-view-btn', function (e) {
    e.preventDefault();
    const pemesananId = $(this).data('id');
    const $modalContent = $('#quickViewModalContent');

    if (!pemesananId || !baseUrl) {
        Swal.fire('Error', 'Data tidak lengkap untuk Quick View.', 'error');
        return;
    }
    if (!quickViewModalInstance) {
        console.warn("QuickViewModalInstance is not initialized! Attempting re-initialization...");
        initializePemesananPage(); // Attempt to re-initialize modal components
        if (!quickViewModalInstance) {
            Swal.fire('Error', 'Komponen modal tidak siap. Coba segarkan halaman.', 'error');
            console.error("QuickViewModalInstance still not initialized!");
            return;
        }
    }

    $modalContent.html(
        `<div class="text-center py-10">
            <svg class="animate-spin h-8 w-8 text-gray-500 dark:text-gray-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Memuat detail...</p>
        </div>`
    );
    quickViewModalInstance.show();

    $.ajax({
        url: `${baseUrl.replace(/\/$/, "")}/${pemesananId}/quick-view`,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success && response.html) {
                $modalContent.html(response.html);
                // Attach listener to dynamic close button ('Tutup' button in loaded content)
                const dynamicCloseButton = document.getElementById('dynamicCloseQuickViewModalButton');
                if (dynamicCloseButton && quickViewModalInstance) {
                    dynamicCloseButton.addEventListener('click', () => {
                        quickViewModalInstance.hide();
                    });
                }
            } else {
                $modalContent.html(`<div class="p-6 text-center text-red-500">${response.message || 'Gagal memuat detail.'}</div>`);
            }
        },
        error: function (xhr) {
            $modalContent.html(`<div class="p-6 text-center text-red-500">Error: ${xhr.status}. Gagal memuat detail.</div>`);
            console.error("Quick view AJAX error:", xhr.responseText);
        }
    });
});

/**
 * Handles the inline update of an order's status via an AJAX PATCH request.
 */
$(document).on('change', '.status-dropdown', function () {
    const $select = $(this);
    const pemesananId = $select.data('id');
    const newStatus = $select.val();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const $spinner = $select.closest('td').find('.status-spinner-' + pemesananId);

    if (typeof $select.data('original-value') === 'undefined') {
        $select.data('original-value', $select.find('option[selected]').val() || $select.find('option:first').val());
    }
    const originalStatus = $select.data('original-value');

    let oldClassesArray = [];
    ($select.attr('class') || "").split(' ').forEach(cls => { if (cls.startsWith('bg-') || cls.startsWith('text-')) oldClassesArray.push(cls); });
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
                if (oldBadgeClasses) $select.removeClass(oldBadgeClasses);
                if (response.status_badge_class) $select.addClass(response.status_badge_class);
                $select.data('original-value', newStatus);
            } else {
                Swal.fire('Gagal', response.message || 'Gagal memperbarui status.', 'error');
                $select.val(originalStatus);
                if (response.status_badge_class) $select.removeClass(response.status_badge_class);
                if (oldBadgeClasses) $select.addClass(oldBadgeClasses);
            }
        },
        error: function (xhr) {
            let errorMsg = (xhr.responseJSON && xhr.responseJSON.message) || xhr.statusText || 'Terjadi kesalahan server.';
            Swal.fire('Error ' + xhr.status, errorMsg, 'error');
            $select.val(originalStatus);
            ($select.attr('class') || "").split(' ').forEach(cls => { if (cls.startsWith('bg-') || cls.startsWith('text-')) $select.removeClass(cls); });
            if (oldBadgeClasses) $select.addClass(oldBadgeClasses);
            console.error("Inline status update error:", xhr.responseText);
        },
        complete: function () {
            $spinner.addClass('hidden');
            $select.prop('disabled', false);
        }
    });
});

/**
 * Handles single order deletion via AJAX.
 */
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
        text: `Hapus pesanan (ID: ${idPemesanan})?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${baseUrl.replace(/\/$/, "")}/single/${idPemesanan}`,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                success: function (response) {
                    if (response.success) {
                        $button.closest('tr').fadeOut(400, function () { $(this).remove(); });
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 1500, showConfirmButton: false });
                        if ($('#pemesananTableContainer tbody tr').not(':has(td[colspan])').length === 0) {
                            fetchPemesanans(1);
                        }
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus pesanan.', 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', `Terjadi kesalahan: ${xhr.statusText}`, 'error');
                }
            });
        }
    });
});

/**
 * Handles bulk order deletion via AJAX.
 */
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
        text: `Hapus ${selectedIds.length} item terpilih?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${baseUrl.replace(/\/$/, "")}/mass-delete`,
                type: 'POST',
                data: { _token: csrfToken, _method: 'DELETE', ids: selectedIds },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                        fetchPemesanans(1);
                        $('#checkbox-all').prop('checked', false);
                    } else {
                        Swal.fire('Gagal', response.message || 'Gagal menghapus item.', 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', `Terjadi kesalahan: ${xhr.statusText}`, 'error');
                }
            });
        }
    });
});

/**
 * Handles browser back/forward button navigation (popstate event).
 */
window.addEventListener('popstate', function (event) {
    if ($('#pemesananTableContainer').length > 0 && $('body').data('pemesanan-initialized')) {
        console.log('Popstate event triggered for Pemesanan. Current URL:', window.location.href);
        initializePemesananPage(); // Re-read params from URL
        const queryParams = new URLSearchParams(window.location.search);
        fetchPemesanans(queryParams.get('page') || 1);
    }
});
