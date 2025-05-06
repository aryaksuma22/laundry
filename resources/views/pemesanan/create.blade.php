<x-app-layout>
    <div class="py-8 px-4 md:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">Tambah Data Pemesanan (Admin)</h2>

            {{-- Validation Error Display --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops! Ada kesalahan:</strong>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pemesanan.store') }}" method="POST"
                class="bg-white shadow-md rounded-lg p-6 md:p-8 space-y-6">
                @csrf

                {{-- Customer Information Section --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Informasi Pelanggan</legend>
                    {{-- ... Fields: nama_pelanggan, kontak_pelanggan, alamat_pelanggan ... --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                Pelanggan<span class="text-red-500 ml-1">*</span></label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                                value="{{ old('nama_pelanggan') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('nama_pelanggan') border-red-500 @enderror"
                                required placeholder="Masukkan nama lengkap">
                            @error('nama_pelanggan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="kontak_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Kontak
                                (WA/Telepon)<span class="text-red-500 ml-1">*</span></label>
                            <input type="text" name="kontak_pelanggan" id="kontak_pelanggan"
                                value="{{ old('kontak_pelanggan') }}" placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('kontak_pelanggan') border-red-500 @enderror"
                                required>
                            @error('kontak_pelanggan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Alamat
                                Pelanggan <span id="alamat_helper_text" class="text-gray-500 text-xs">(Opsional, isi
                                    jika perlu antar/jemput)</span></label>
                            <textarea name="alamat_pelanggan" id="alamat_pelanggan" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('alamat_pelanggan') border-red-500 @enderror"
                                placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat_pelanggan') }}</textarea>
                            @error('alamat_pelanggan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Order Details Section --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Detail Pesanan</legend>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        {{-- Fields: layanan_utama_id, kecepatan_layanan, metode_layanan --}}
                        <div>
                            <label for="layanan_utama_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan
                                Utama<span class="text-red-500 ml-1">*</span></label>
                            <select name="layanan_utama_id" id="layanan_utama_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('layanan_utama_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach ($layanans ?? [] as $layanan)
                                    <option value="{{ $layanan->id }}"
                                        {{ old('layanan_utama_id') == $layanan->id ? 'selected' : '' }}
                                        data-harga="{{ $layanan->harga ?? 0 }}">
                                        {{ $layanan->nama_layanan }} (Rp
                                        {{ number_format($layanan->harga ?? 0, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('layanan_utama_id')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="kecepatan_layanan"
                                class="block text-sm font-medium text-gray-700 mb-1">Kecepatan Layanan<span
                                    class="text-red-500 ml-1">*</span></label>
                            <select name="kecepatan_layanan" id="kecepatan_layanan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('kecepatan_layanan') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Kecepatan --</option>
                                @foreach ($kecepatanOptions ?? ['Reguler', 'Express', 'Kilat'] as $item)
                                    <option value="{{ $item }}"
                                        {{ old('kecepatan_layanan', 'Reguler') == $item ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('kecepatan_layanan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="metode_layanan" class="block text-sm font-medium text-gray-700 mb-1">Metode
                                Layanan<span class="text-red-500 ml-1">*</span></label>
                            <select name="metode_layanan" id="metode_layanan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('metode_layanan') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach ($metodeOptions ?? ['Antar Jemput', 'Datang Langsung', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri'] as $item)
                                    <option value="{{ $item }}"
                                        {{ old('metode_layanan', 'Datang Langsung') == $item ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('metode_layanan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        {{-- Fields: estimasi_berat, daftar_item, catatan_pelanggan --}}
                        <div>
                            <label for="estimasi_berat" class="block text-sm font-medium text-gray-700 mb-1">Estimasi
                                Berat (Kg) <span class="text-gray-500 text-xs">(Jika kiloan)</span></label>
                            <input type="number" step="0.01" name="estimasi_berat" id="estimasi_berat"
                                value="{{ old('estimasi_berat') }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('estimasi_berat') border-red-500 @enderror"
                                placeholder="Contoh: 5.5">
                            @error('estimasi_berat')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="daftar_item" class="block text-sm font-medium text-gray-700 mb-1">Daftar Item
                                <span class="text-gray-500 text-xs">(Jika layanan satuan/penting)</span></label>
                            <textarea name="daftar_item" id="daftar_item" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('daftar_item') border-red-500 @enderror"
                                placeholder="Contoh: 3 Kemeja, 2 Celana, 1 Jaket Kulit">{{ old('daftar_item') }}</textarea>
                            @error('daftar_item')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-3">
                            <label for="catatan_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                Pelanggan <span class="text-gray-500 text-xs">(Instruksi khusus)</span></label>
                            <textarea name="catatan_pelanggan" id="catatan_pelanggan" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('catatan_pelanggan') border-red-500 @enderror"
                                placeholder="Jangan pakai pewangi, ada noda di baju X, dll">{{ old('catatan_pelanggan') }}</textarea>
                            @error('catatan_pelanggan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Conditional Pickup/Address Fields --}}
                    <div id="penjemputan_fields" class="hidden mt-6"> {{-- ... content ... --}}
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-blue-200 rounded-md bg-blue-50">
                            <h4 class="md:col-span-2 text-md font-semibold text-blue-800 mb-2">Detail Penjemputan</h4>
                            <div>
                                <label for="tanggal_penjemputan"
                                    class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penjemputan <span
                                        class="text-red-500 ml-1">*</span></label>
                                <input type="date" name="tanggal_penjemputan" id="tanggal_penjemputan"
                                    value="{{ old('tanggal_penjemputan') }}" min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_penjemputan') border-red-500 @enderror">
                                @error('tanggal_penjemputan')
                                    <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="waktu_penjemputan"
                                    class="block text-sm font-medium text-gray-700 mb-1">Waktu Penjemputan <span
                                        class="text-red-500 ml-1">*</span></label>
                                <select name="waktu_penjemputan" id="waktu_penjemputan"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('waktu_penjemputan') border-red-500 @enderror">
                                    <option value="">-- Pilih Slot Waktu --</option>
                                    <option value="Pagi (09:00 - 12:00)"
                                        {{ old('waktu_penjemputan') == 'Pagi (09:00 - 12:00)' ? 'selected' : '' }}>Pagi
                                        (09:00 - 12:00)</option>
                                    <option value="Siang (13:00 - 16:00)"
                                        {{ old('waktu_penjemputan') == 'Siang (13:00 - 16:00)' ? 'selected' : '' }}>
                                        Siang (13:00 - 16:00)</option>
                                    <option value="Sore (16:00 - 18:00)"
                                        {{ old('waktu_penjemputan') == 'Sore (16:00 - 18:00)' ? 'selected' : '' }}>Sore
                                        (16:00 - 18:00)</option>
                                </select>
                                @error('waktu_penjemputan')
                                    <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div id="instruksi_alamat_field" class="hidden mt-6"> {{-- ... content ... --}}
                        <label for="instruksi_alamat" class="block text-sm font-medium text-gray-700 mb-1">Instruksi
                            Alamat/Penjemputan <span class="text-gray-500 text-xs">(Patokan, dll)</span></label>
                        <textarea name="instruksi_alamat" id="instruksi_alamat" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('instruksi_alamat') border-red-500 @enderror"
                            placeholder="Patokan dekat masjid, titip di satpam, dll">{{ old('instruksi_alamat') }}</textarea>
                        @error('instruksi_alamat')
                            <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </fieldset>

                {{-- Admin Input Section (Results & Costs) --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Hasil & Biaya (Admin)</legend>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">

                        {{-- === Conditional Field: Berat Final (untuk Kiloan) === --}}
                        <div id="berat_final_field">
                            <label for="berat_final" class="block text-sm font-medium text-gray-700 mb-1">
                                Berat Final <span class="text-blue-600 font-semibold">(Kg)</span> <span
                                    class="text-gray-500 text-xs">(untuk Kiloan)</span>
                            </label>
                            <input type="number" step="0.01" name="berat_final" id="berat_final"
                                value="{{ old('berat_final') }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('berat_final') border-red-500 @enderror"
                                placeholder="Contoh: 5.75">
                            @error('berat_final')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- === Conditional Field: Jumlah Item (untuk Satuan) === --}}
                        <div id="jumlah_item_field" style="display: none;"> {{-- Start hidden --}}
                            <label for="jumlah_item_input" class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Item <span class="text-blue-600 font-semibold">(Pcs)</span> <span
                                    class="text-gray-500 text-xs">(untuk Satuan)</span>
                            </label>
                            <input type="number" step="1" name="jumlah_item_input" id="jumlah_item_input"
                                value="{{ old('jumlah_item_input', 1) }}" min="1" {{-- Default to 1 for satuan --}}
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('jumlah_item_input') border-red-500 @enderror"
                                placeholder="Jumlah barang">
                            {{-- Note: No backend validation for jumlah_item_input added here, adjust if needed --}}
                        </div>

                        {{-- Total Price Display & Hidden Input --}}
                        <div class="md:col-span-2">
                            <label for="total_harga_display" class="block text-sm font-medium text-gray-700 mb-1">
                                Total Harga (Rp) <span class="text-blue-600 text-xs font-semibold">(Otomatis)</span>
                            </label>
                            <input type="text" id="total_harga" name="total_harga_display"
                                value="{{ old('total_harga') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 @error('total_harga') border-red-500 @enderror"
                                placeholder="Akan terhitung otomatis" readonly>
                            <input type="hidden" name="total_harga" id="total_harga_numeric"
                                value="{{ old('total_harga', 0) }}">
                            @error('total_harga')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror

                            {{-- PRICE BREAKDOWN SECTION --}}
                            <div id="price-breakdown" class="mt-2 text-sm text-gray-600 border-t pt-2 hidden">
                                <h4 class="font-semibold mb-1 text-gray-700">Rincian Perhitungan:</h4>
                                <div class="space-y-1">
                                    <div id="breakdown-item-service">
                                        <span class="font-medium">Layanan (<span
                                                id="breakdown-service-name">-</span>):</span>
                                        <span class="float-right" id="breakdown-service-total">Rp 0</span>
                                        <span class="block text-xs text-gray-500 clear-both"
                                            id="breakdown-service-calc-info"></span>
                                    </div>
                                    <div id="breakdown-item-speed" style="display: none;">
                                        <span class="font-medium">Biaya Kecepatan (<span
                                                id="breakdown-speed-name">-</span>):</span>
                                        <span class="float-right" id="breakdown-speed-cost">Rp 0</span>
                                    </div>
                                    <div id="breakdown-item-method" style="display: none;">
                                        <span class="font-medium">Biaya Metode (<span
                                                id="breakdown-method-name">-</span>):</span>
                                        <span class="float-right" id="breakdown-method-cost">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            {{-- END PRICE BREAKDOWN SECTION --}}
                        </div>
                    </div>
                </fieldset>

                {{-- Status & Completion Section --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Status & Penyelesaian</legend>
                    {{-- ... Fields: status_pesanan, tanggal_selesai ... --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="status_pesanan" class="block text-sm font-medium text-gray-700 mb-1">Status
                                Pesanan<span class="text-red-500 ml-1">*</span></label>
                            <select name="status_pesanan" id="status_pesanan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('status_pesanan') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Status --</option>
                                @foreach ($statuses ?? ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status_pesanan', 'Baru') == $status ? 'selected' : '' }}>
                                        {{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status_pesanan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Selesai <span class="text-gray-500 text-xs">(Otomatis/Manual jika
                                    selesai)</span></label>
                            <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai"
                                value="{{ old('tanggal_selesai') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_selesai') border-red-500 @enderror">
                            @error('tanggal_selesai')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>


                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row gap-4 mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-semibold inline-flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Simpan Pesanan</span>
                    </button>
                    <a href="{{ route('pemesanan.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 font-semibold inline-flex items-center justify-center shadow-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Load AutoNumeric --}}
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>

    <script>
        // --- Get Additional Costs Data from PHP ---
        const biayaTambahan = @json($biayaTambahan ?? ['kecepatan' => [], 'metode' => []]);
        let autoNumericInstance = null; // To hold the AutoNumeric object

        // Helper function to format currency (using AutoNumeric for consistency)
        const formatCurrency = (value) => {
            const tempInput = document.createElement('input');
            tempInput.type = 'text';
            let formatted = 'Rp 0'; // Default
            try {
                document.body.appendChild(tempInput);
                const tempAn = new AutoNumeric(tempInput, {
                    currencySymbol: 'Rp ',
                    decimalCharacter: ',',
                    digitGroupSeparator: '.',
                    decimalPlaces: 0
                });
                tempAn.set(value);
                formatted = tempAn.getFormatted();
                tempAn.remove();
            } catch (e) {
                console.error("Error formatting currency:", e);
                formatted = 'Rp ' + (value ? Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '0');
            } finally {
                if (tempInput.parentNode === document.body) {
                    document.body.removeChild(tempInput);
                }
            }
            return formatted;
        };


        document.addEventListener("DOMContentLoaded", function() {
            // --- References to Form Elements ---
            const layananSelect = document.getElementById('layanan_utama_id');
            const kecepatanSelect = document.getElementById('kecepatan_layanan');
            const metodeSelect = document.getElementById('metode_layanan');
            const totalHargaDisplayInput = document.getElementById('total_harga'); // Visible one
            const totalHargaNumericInput = document.getElementById('total_harga_numeric'); // Hidden one

            // --- NEW/MODIFIED References ---
            const beratFinalField = document.getElementById('berat_final_field');
            const beratFinalInput = document.getElementById('berat_final'); // Input inside the field
            const jumlahItemField = document.getElementById('jumlah_item_field');
            const jumlahItemInput = document.getElementById('jumlah_item_input'); // Input inside the field

            // --- References to Breakdown Elements ---
            const breakdownDiv = document.getElementById('price-breakdown');
            const breakdownItemServiceDiv = document.getElementById('breakdown-item-service');
            const breakdownServiceNameEl = document.getElementById('breakdown-service-name');
            const breakdownServiceTotalEl = document.getElementById('breakdown-service-total');
            const breakdownServiceCalcInfoEl = document.getElementById('breakdown-service-calc-info');
            const breakdownItemSpeedDiv = document.getElementById('breakdown-item-speed');
            const breakdownSpeedNameEl = document.getElementById('breakdown-speed-name');
            const breakdownSpeedCostEl = document.getElementById('breakdown-speed-cost');
            const breakdownItemMethodDiv = document.getElementById('breakdown-item-method');
            const breakdownMethodNameEl = document.getElementById('breakdown-method-name');
            const breakdownMethodCostEl = document.getElementById('breakdown-method-cost');


            // --- Initialize AutoNumeric on the DISPLAY input ---
            try {
                if (totalHargaDisplayInput) {
                    autoNumericInstance = new AutoNumeric(totalHargaDisplayInput, {
                        currencySymbol: 'Rp ',
                        decimalCharacter: ',',
                        digitGroupSeparator: '.',
                        decimalPlaces: 0,
                        readOnly: true
                    });
                } else {
                    console.error("Element 'total_harga' not found for AutoNumeric.");
                }
            } catch (e) {
                console.error("AutoNumeric initialization failed:", e);
            }


            // --- Function to Toggle Weight/Quantity Inputs ---
            function toggleInputFieldsBasedOnServiceType() {
                if (!layananSelect || !beratFinalField || !jumlahItemField) return; // Safety check

                const selectedLayananOption = layananSelect.options[layananSelect.selectedIndex];
                const serviceName = selectedLayananOption?.text || '';
                let serviceType = 'satuan'; // Default

                if (serviceName && serviceName.toLowerCase().includes('kiloan')) {
                    serviceType = 'kiloan';
                }

                if (serviceType === 'kiloan') {
                    beratFinalField.style.display = 'block';
                    jumlahItemField.style.display = 'none';
                    if (jumlahItemInput) jumlahItemInput.value = ''; // Clear quantity when switching to kiloan
                } else { // Satuan or other
                    beratFinalField.style.display = 'none';
                    jumlahItemField.style.display = 'block';
                    if (beratFinalInput) beratFinalInput.value = ''; // Clear weight when switching to satuan
                    if (jumlahItemInput && !jumlahItemInput.value) jumlahItemInput.value =
                    1; // Default quantity to 1 if empty
                }
            }


            // --- Total Price Calculation Function ---
            function calculateTotalPrice() {
                // Add checks for the potentially hidden inputs
                if (!layananSelect || !kecepatanSelect || !metodeSelect || !autoNumericInstance || !
                    totalHargaNumericInput || !breakdownDiv ||
                    !beratFinalInput || !jumlahItemInput || // Check inputs themselves
                    !breakdownItemServiceDiv || !breakdownServiceNameEl || !breakdownServiceTotalEl || !
                    breakdownServiceCalcInfoEl ||
                    !breakdownItemSpeedDiv || !breakdownSpeedNameEl || !breakdownSpeedCostEl ||
                    !breakdownItemMethodDiv || !breakdownMethodNameEl || !breakdownMethodCostEl
                ) {
                    console.warn("Calculation skipped: One or more required elements missing.");
                    return;
                }

                // Get values
                const selectedLayananOption = layananSelect.options[layananSelect.selectedIndex];
                const basePrice = parseFloat(selectedLayananOption?.dataset.harga || 0);
                const serviceName = selectedLayananOption?.text.split(' (Rp')[0].trim() || '';

                // Get values from *currently relevant* input (weight or quantity)
                const beratFinal = parseFloat(beratFinalInput.value) || 0;
                const quantity = parseInt(jumlahItemInput.value) || 1; // Use parseInt, default to 1

                const selectedKecepatan = kecepatanSelect.value;
                const selectedMetode = metodeSelect.value;

                // Infer service type
                let serviceType = 'satuan';
                if (serviceName && serviceName.toLowerCase().includes('kiloan')) {
                    serviceType = 'kiloan';
                }

                // Calculate components based on type
                let calculatedPrice = 0;
                let serviceCalcInfoText = '';
                if (layananSelect.value === "") {
                    calculatedPrice = 0;
                } else if (serviceType === 'kiloan') {
                    calculatedPrice = basePrice * beratFinal;
                    serviceCalcInfoText =
                        `${formatCurrency(basePrice)}/kg × ${beratFinal.toLocaleString('id-ID')} kg`;
                } else { // Satuan or other
                    calculatedPrice = basePrice * quantity;
                    serviceCalcInfoText = `${formatCurrency(basePrice)}/pcs × ${quantity} pcs`;
                }

                const biayaKecepatan = biayaTambahan.kecepatan[selectedKecepatan] || 0;
                const biayaMetode = biayaTambahan.metode[selectedMetode] || 0;
                const finalPrice = calculatedPrice + biayaKecepatan + biayaMetode;

                // Update Inputs
                autoNumericInstance.set(finalPrice);
                totalHargaNumericInput.value = finalPrice;

                // Update Breakdown
                if (layananSelect.value !== "") {
                    breakdownDiv.classList.remove('hidden');
                    breakdownServiceNameEl.textContent = serviceName || '-';
                    breakdownServiceTotalEl.textContent = formatCurrency(calculatedPrice);
                    breakdownServiceCalcInfoEl.textContent = serviceCalcInfoText;
                    breakdownItemServiceDiv.style.display = 'block';

                    breakdownSpeedNameEl.textContent = selectedKecepatan || '-';
                    breakdownSpeedCostEl.textContent = formatCurrency(biayaKecepatan);
                    breakdownItemSpeedDiv.style.display = biayaKecepatan > 0 ? 'block' : 'none';

                    breakdownMethodNameEl.textContent = selectedMetode || '-';
                    breakdownMethodCostEl.textContent = formatCurrency(biayaMetode);
                    breakdownItemMethodDiv.style.display = biayaMetode > 0 ? 'block' : 'none';
                } else {
                    breakdownDiv.classList.add('hidden');
                    // Clear breakdown values...
                    breakdownServiceNameEl.textContent = '-';
                    breakdownServiceTotalEl.textContent = formatCurrency(0);
                    breakdownServiceCalcInfoEl.textContent = '';
                    breakdownSpeedNameEl.textContent = '-';
                    breakdownSpeedCostEl.textContent = formatCurrency(0);
                    breakdownMethodNameEl.textContent = '-';
                    breakdownMethodCostEl.textContent = formatCurrency(0);
                    breakdownItemSpeedDiv.style.display = 'none';
                    breakdownItemMethodDiv.style.display = 'none';
                }
            }

            // --- Event Listeners ---
            layananSelect?.addEventListener('change', () => {
                toggleInputFieldsBasedOnServiceType(); // Toggle fields first
                calculateTotalPrice(); // Then calculate price
            });
            kecepatanSelect?.addEventListener('change', calculateTotalPrice);
            metodeSelect?.addEventListener('change', calculateTotalPrice);
            beratFinalInput?.addEventListener('input', calculateTotalPrice); // Listen to weight
            jumlahItemInput?.addEventListener('input', calculateTotalPrice); // Listen to quantity

            // --- Initial Setup on Load ---
            toggleInputFieldsBasedOnServiceType(); // Set initial visibility
            calculateTotalPrice(); // Calculate initial price

            // --- Conditional Fields Logic for Pickup/Address ---
            const penjemputanFieldsDiv = document.getElementById('penjemputan_fields');
            const instruksiAlamatFieldDiv = document.getElementById('instruksi_alamat_field');
            const tanggalJemputInput = document.getElementById('tanggal_penjemputan');
            const waktuJemputInput = document.getElementById('waktu_penjemputan');
            const alamatHelperText = document.getElementById('alamat_helper_text');
            const metodePerluJemput = ['Antar Jemput', 'Minta Dijemput Ambil Sendiri'];
            const metodePerluInstruksiDanAlamat = ['Antar Jemput', 'Antar Sendiri Minta Diantar',
                'Minta Dijemput Ambil Sendiri'
            ];

            function toggleConditionalFields() { // Renamed to avoid conflict
                if (!metodeSelect) return;
                const selectedMetode = metodeSelect.value;
                let showPenjemputan = metodePerluJemput.includes(selectedMetode);
                let showInstruksi = metodePerluInstruksiDanAlamat.includes(selectedMetode);
                let isAlamatWajib = metodePerluInstruksiDanAlamat.includes(selectedMetode);

                if (penjemputanFieldsDiv) {
                    penjemputanFieldsDiv.style.display = showPenjemputan ? 'block' : 'none';
                    if (tanggalJemputInput) tanggalJemputInput.required = showPenjemputan;
                    if (waktuJemputInput) waktuJemputInput.required = showPenjemputan;
                }
                if (instruksiAlamatFieldDiv) {
                    instruksiAlamatFieldDiv.style.display = showInstruksi ? 'block' : 'none';
                }
                if (alamatHelperText) {
                    if (isAlamatWajib) {
                        alamatHelperText.textContent = '(Wajib diisi jika ada antar/jemput)';
                        alamatHelperText.classList.add('text-red-600', 'font-semibold');
                        alamatHelperText.classList.remove('text-gray-500');
                    } else {
                        alamatHelperText.textContent = '(Opsional, isi jika perlu antar/jemput)';
                        alamatHelperText.classList.remove('text-red-600', 'font-semibold');
                        alamatHelperText.classList.add('text-gray-500');
                    }
                }
            }

            if (metodeSelect) {
                toggleConditionalFields();
                metodeSelect.addEventListener('change', toggleConditionalFields);
            } else {
                console.warn("Element #metode_layanan not found for conditional fields.");
            }

        }); // End DOMContentLoaded
    </script>

</x-app-layout>
