// public/js/guest_order_form.js

document.addEventListener('DOMContentLoaded', function() {
    // Elements for conditional fields
    const metodeLayananSelect = document.getElementById('metode_layanan');
    const alamatSection = document.getElementById('alamat_section');
    const alamatInput = document.getElementById('alamat_pelanggan');
    const alamatRequiredStar = document.getElementById('alamat_required_star');
    const penjemputanSection = document.getElementById('penjemputan_section');
    const tanggalPenjemputanInput = document.getElementById('tanggal_penjemputan');
    const waktuPenjemputanInput = document.getElementById('waktu_penjemputan');
    const instruksiAlamatSection = document.getElementById('instruksi_alamat_section');

    const layananUtamaSelect = document.getElementById('layanan_utama_id');
    const estimasiBeratSection = document.getElementById('estimasi_berat_section');
    const estimasiBeratInput = document.getElementById('estimasi_berat');
    const daftarItemSection = document.getElementById('daftar_item_section');

    // Elements for price estimation
    const kecepatanLayananSelect = document.getElementById('kecepatan_layanan');
    const estimasiHargaDisplay = document.getElementById('estimasi_harga_display');

    // Helper function to format number as currency
    function formatCurrency(amount) {
        return 'Rp ' + parseFloat(amount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Function to calculate and update estimated price
    function updateEstimasiHarga() {
        // Pastikan BIAYA_TAMBAHAN tersedia (akan didefinisikan di Blade)
        if (typeof BIAYA_TAMBAHAN === 'undefined') {
            console.error('BIAYA_TAMBAHAN is not defined. Make sure it is passed from Blade.');
            if(estimasiHargaDisplay) estimasiHargaDisplay.textContent = 'Error memuat harga';
            return;
        }

        if (!layananUtamaSelect || !kecepatanLayananSelect || !metodeLayananSelect || !estimasiHargaDisplay) {
            console.warn('One or more elements for price calculation are missing.');
            if(estimasiHargaDisplay) estimasiHargaDisplay.textContent = 'Rp 0';
            return;
        }

        let totalEstimasi = 0;
        let hargaLayananDasar = 0; // Perbaikan typo: hargaLayanan Dasar -> hargaLayananDasar

        // 1. Get base service price and type
        const selectedLayananOption = layananUtamaSelect.options[layananUtamaSelect.selectedIndex];
        const layananType = selectedLayananOption ? selectedLayananOption.dataset.type : null;
        const hargaPerUnitLayanan = selectedLayananOption ? parseFloat(selectedLayananOption.dataset.harga || 0) : 0;

        if (layananType === 'kiloan') {
            const berat = estimasiBeratInput ? (parseFloat(estimasiBeratInput.value) || 0) : 0;
            hargaLayananDasar = hargaPerUnitLayanan * Math.max(0, berat);
        } else if (layananType === 'satuan') {
            hargaLayananDasar = hargaPerUnitLayanan;
        }
        totalEstimasi += hargaLayananDasar;

        // 2. Get additional cost for service speed
        const selectedKecepatan = kecepatanLayananSelect.value;
        const biayaKecepatan = (BIAYA_TAMBAHAN.kecepatan && BIAYA_TAMBAHAN.kecepatan[selectedKecepatan]) || 0;
        totalEstimasi += biayaKecepatan;

        // 3. Get additional cost for service method
        const selectedMetode = metodeLayananSelect.value;
        const biayaMetode = (BIAYA_TAMBAHAN.metode && BIAYA_TAMBAHAN.metode[selectedMetode]) || 0;
        totalEstimasi += biayaMetode;

        estimasiHargaDisplay.textContent = formatCurrency(totalEstimasi);
    }

    // Function to toggle conditional fields
    function toggleConditionalFields() {
        if (!metodeLayananSelect) return;
        const selectedMetode = metodeLayananSelect.value;
        const needsPickup = ['Antar Jemput', 'Minta Dijemput Ambil Sendiri'].includes(selectedMetode);
        const needsDeliveryAddress = ['Antar Jemput', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri'].includes(selectedMetode);

        if (alamatSection) {
            if (needsDeliveryAddress) {
                alamatSection.classList.remove('hidden');
                if (alamatInput) alamatInput.required = true;
                if (alamatRequiredStar) alamatRequiredStar.classList.remove('hidden');
                if (instruksiAlamatSection) instruksiAlamatSection.classList.remove('hidden');
            } else {
                alamatSection.classList.add('hidden');
                if (alamatInput) alamatInput.required = false;
                if (alamatRequiredStar) alamatRequiredStar.classList.add('hidden');
                if (instruksiAlamatSection) instruksiAlamatSection.classList.add('hidden');
            }
        }

        if (penjemputanSection) {
            if (needsPickup) {
                penjemputanSection.classList.remove('hidden');
                if (tanggalPenjemputanInput) tanggalPenjemputanInput.required = true;
                if (waktuPenjemputanInput) waktuPenjemputanInput.required = true;
            } else {
                penjemputanSection.classList.add('hidden');
                if (tanggalPenjemputanInput) tanggalPenjemputanInput.required = false;
                if (waktuPenjemputanInput) waktuPenjemputanInput.required = false;
            }
        }
        updateEstimasiHarga();
    }

    // Function to toggle service detail fields
    function toggleLayananDetailFields() {
        if (!layananUtamaSelect || !estimasiBeratSection || !daftarItemSection) return;
        const selectedLayananOption = layananUtamaSelect.options[layananUtamaSelect.selectedIndex];

        if (!selectedLayananOption || !selectedLayananOption.dataset.type) {
            estimasiBeratSection.classList.add('hidden');
            daftarItemSection.classList.add('hidden');
            if (estimasiBeratInput) estimasiBeratInput.required = false;
            updateEstimasiHarga();
            return;
        }

        const layananType = selectedLayananOption.dataset.type;

        if (layananType === 'kiloan') {
            estimasiBeratSection.classList.remove('hidden');
            // estimasiBeratInput.required is handled by backend validation
            daftarItemSection.classList.add('hidden');
        } else if (layananType === 'satuan') {
            estimasiBeratSection.classList.add('hidden');
            // estimasiBeratInput.required is handled by backend validation
            daftarItemSection.classList.remove('hidden');
        } else {
            estimasiBeratSection.classList.add('hidden');
            daftarItemSection.classList.add('hidden');
            // estimasiBeratInput.required is handled by backend validation
        }
        updateEstimasiHarga();
    }

    // Initial setup and event listeners
    // Ensure elements exist before adding listeners
    if (metodeLayananSelect) {
        metodeLayananSelect.addEventListener('change', toggleConditionalFields);
        toggleConditionalFields();
    }
    if (layananUtamaSelect) {
        layananUtamaSelect.addEventListener('change', toggleLayananDetailFields);
        toggleLayananDetailFields();
    }
    if (kecepatanLayananSelect) {
        kecepatanLayananSelect.addEventListener('change', updateEstimasiHarga);
    }
    if (estimasiBeratInput) {
        estimasiBeratInput.addEventListener('input', updateEstimasiHarga);
    }

    // Initial price calculation on page load
    updateEstimasiHarga();
});
