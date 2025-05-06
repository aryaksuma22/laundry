<x-app-layout>
    <div class="py-8 px-4 md:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            {{-- Changed Heading --}}
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">Edit Data Pemesanan
                #{{ $pemesanan->no_pesanan }}</h2>

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

            {{-- Changed form action and method --}}
            <form action="{{ route('pemesanan.update', $pemesanan->id) }}" method="POST"
                class="bg-white shadow-md rounded-lg p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT') {{-- Use PUT for update --}}

                {{-- Customer Information Section --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Informasi Pelanggan</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        {{-- Nama Pelanggan --}}
                        <div>
                            <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                Pelanggan<span class="text-red-500 ml-1">*</span></label>
                            {{-- Pre-fill value --}}
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                                value="{{ old('nama_pelanggan', $pemesanan->nama_pelanggan) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('nama_pelanggan') border-red-500 @enderror"
                                required>
                            @error('nama_pelanggan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Kontak --}}
                        <div>
                            <label for="kontak_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Kontak
                                (WA/Telepon)<span class="text-red-500 ml-1">*</span></label>
                            {{-- Pre-fill value --}}
                            <input type="text" name="kontak_pelanggan" id="kontak_pelanggan"
                                value="{{ old('kontak_pelanggan', $pemesanan->kontak_pelanggan) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('kontak_pelanggan') border-red-500 @enderror"
                                required>
                            @error('kontak_pelanggan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Alamat Pelanggan --}}
                        <div class="md:col-span-2">
                            <label for="alamat_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Alamat
                                Pelanggan <span id="alamat_helper_text" class="text-gray-500 text-xs">(Opsional, isi
                                    jika perlu antar/jemput)</span></label>
                            {{-- Pre-fill value --}}
                            <textarea name="alamat_pelanggan" id="alamat_pelanggan" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('alamat_pelanggan') border-red-500 @enderror">{{ old('alamat_pelanggan', $pemesanan->alamat_pelanggan) }}</textarea>
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
                        {{-- Main Service --}}
                        <div>
                            <label for="layanan_utama_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan
                                Utama<span class="text-red-500 ml-1">*</span></label>
                            <select name="layanan_utama_id" id="layanan_utama_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('layanan_utama_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach ($layanans ?? [] as $layanan)
                                    <option value="{{ $layanan->id }}" {{-- Pre-select based on existing data --}}
                                        {{ old('layanan_utama_id', $pemesanan->layanan_utama_id) == $layanan->id ? 'selected' : '' }}
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
                        {{-- Service Speed --}}
                        <div>
                            <label for="kecepatan_layanan"
                                class="block text-sm font-medium text-gray-700 mb-1">Kecepatan Layanan<span
                                    class="text-red-500 ml-1">*</span></label>
                            <select name="kecepatan_layanan" id="kecepatan_layanan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('kecepatan_layanan') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Kecepatan --</option>
                                @foreach ($kecepatanOptions ?? ['Reguler', 'Express', 'Kilat'] as $item)
                                    {{-- Pre-select based on existing data --}}
                                    <option value="{{ $item }}"
                                        {{ old('kecepatan_layanan', $pemesanan->kecepatan_layanan) == $item ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('kecepatan_layanan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Service Method --}}
                        <div>
                            <label for="metode_layanan" class="block text-sm font-medium text-gray-700 mb-1">Metode
                                Layanan<span class="text-red-500 ml-1">*</span></label>
                            <select name="metode_layanan" id="metode_layanan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('metode_layanan') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach ($metodeOptions ?? ['Antar Jemput', 'Datang Langsung', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri'] as $item)
                                    {{-- Pre-select based on existing data --}}
                                    <option value="{{ $item }}"
                                        {{ old('metode_layanan', $pemesanan->metode_layanan) == $item ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('metode_layanan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        {{-- Estimated Weight --}}
                        <div>
                            <label for="estimasi_berat" class="block text-sm font-medium text-gray-700 mb-1">Estimasi
                                Berat (Kg) <span class="text-gray-500 text-xs">(Info Awal)</span></label>
                            {{-- Pre-fill value --}}
                            <input type="number" step="0.01" name="estimasi_berat" id="estimasi_berat"
                                value="{{ old('estimasi_berat', $pemesanan->estimasi_berat) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('estimasi_berat') border-red-500 @enderror"
                                placeholder="Contoh: 5.5">
                            @error('estimasi_berat')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Item List --}}
                        <div class="md:col-span-2">
                            <label for="daftar_item" class="block text-sm font-medium text-gray-700 mb-1">Daftar Item
                                <span class="text-gray-500 text-xs">(Jika layanan satuan/penting)</span></label>
                            {{-- Pre-fill value --}}
                            <textarea name="daftar_item" id="daftar_item" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('daftar_item') border-red-500 @enderror"
                                placeholder="Contoh: 3 Kemeja, 2 Celana, 1 Jaket Kulit">{{ old('daftar_item', $pemesanan->daftar_item) }}</textarea>
                            @error('daftar_item')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Customer Notes --}}
                        <div class="md:col-span-3">
                            <label for="catatan_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                Pelanggan <span class="text-gray-500 text-xs">(Instruksi khusus)</span></label>
                            {{-- Pre-fill value --}}
                            <textarea name="catatan_pelanggan" id="catatan_pelanggan" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('catatan_pelanggan') border-red-500 @enderror"
                                placeholder="Jangan pakai pewangi, ada noda di baju X, dll">{{ old('catatan_pelanggan', $pemesanan->catatan_pelanggan) }}</textarea>
                            @error('catatan_pelanggan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Conditional Pickup/Address Fields --}}
                    <div id="penjemputan_fields" class="hidden mt-6">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-blue-200 rounded-md bg-blue-50">
                            <h4 class="md:col-span-2 text-md font-semibold text-blue-800 mb-2">Detail Penjemputan</h4>
                            <div>
                                <label for="tanggal_penjemputan"
                                    class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penjemputan <span
                                        class="text-red-500 ml-1">*</span></label>
                                {{-- Pre-fill value (format date for input type="date") --}}
                                <input type="date" name="tanggal_penjemputan" id="tanggal_penjemputan"
                                    value="{{ old('tanggal_penjemputan', $pemesanan->tanggal_penjemputan ? \Carbon\Carbon::parse($pemesanan->tanggal_penjemputan)->format('Y-m-d') : '') }}"
                                    min="{{ date('Y-m-d') }}"
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
                                        {{ old('waktu_penjemputan', $pemesanan->waktu_penjemputan) == 'Pagi (09:00 - 12:00)' ? 'selected' : '' }}>
                                        Pagi (09:00 - 12:00)</option>
                                    <option value="Siang (13:00 - 16:00)"
                                        {{ old('waktu_penjemputan', $pemesanan->waktu_penjemputan) == 'Siang (13:00 - 16:00)' ? 'selected' : '' }}>
                                        Siang (13:00 - 16:00)</option>
                                    <option value="Sore (16:00 - 18:00)"
                                        {{ old('waktu_penjemputan', $pemesanan->waktu_penjemputan) == 'Sore (16:00 - 18:00)' ? 'selected' : '' }}>
                                        Sore (16:00 - 18:00)</option>
                                </select>
                                @error('waktu_penjemputan')
                                    <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div id="instruksi_alamat_field" class="hidden mt-6">
                        <label for="instruksi_alamat" class="block text-sm font-medium text-gray-700 mb-1">Instruksi
                            Alamat/Penjemputan <span class="text-gray-500 text-xs">(Patokan, dll)</span></label>
                        {{-- Pre-fill value --}}
                        <textarea name="instruksi_alamat" id="instruksi_alamat" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('instruksi_alamat') border-red-500 @enderror"
                            placeholder="Patokan dekat masjid, titip di satpam, dll">{{ old('instruksi_alamat', $pemesanan->instruksi_alamat) }}</textarea>
                        @error('instruksi_alamat')
                            <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </fieldset>

                {{-- Admin Input Section (Results & Costs) --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Hasil & Biaya (Admin Update)</legend>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">

                        {{-- Conditional Field: Berat Final --}}
                        <div id="berat_final_field">
                            <label for="berat_final" class="block text-sm font-medium text-gray-700 mb-1">Berat Final
                                <span class="text-blue-600 font-semibold">(Kg)</span> <span
                                    class="text-gray-500 text-xs">(untuk Kiloan)</span></label>
                            {{-- Pre-fill value --}}
                            <input type="number" step="0.01" name="berat_final" id="berat_final"
                                value="{{ old('berat_final', $pemesanan->berat_final) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('berat_final') border-red-500 @enderror"
                                placeholder="Contoh: 5.75">
                            @error('berat_final')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Conditional Field: Jumlah Item --}}
                        <div id="jumlah_item_field" style="display: none;">
                            <label for="jumlah_item_input" class="block text-sm font-medium text-gray-700 mb-1">Jumlah
                                Item <span class="text-blue-600 font-semibold">(Pcs)</span> <span
                                    class="text-gray-500 text-xs">(untuk Satuan)</span></label>
                            {{-- **** REMOVED name attribute initially **** --}}
                            <input type="number" step="1" id="jumlah_item_input" {{-- No name here --}}
                                value="{{ old('berat_final', $pemesanan->berat_final ?? 1) }}" {{-- Still use old('berat_final') if validation fails --}}
                                min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('berat_final') border-red-500 @enderror"
                                {{-- Error key remains 'berat_final' --}} placeholder="Jumlah barang">
                            {{-- Error message still checks 'berat_final' because that's what's submitted --}}
                            @error('berat_final')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Total Price Display & Hidden Input --}}
                        <div class="md:col-span-2">
                            <label for="total_harga_display"
                                class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp) <span
                                    class="text-blue-600 text-xs font-semibold">(Otomatis)</span></label>
                            <input type="text" id="total_harga" name="total_harga_display"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 @error('total_harga') border-red-500 @enderror"
                                placeholder="Akan terhitung otomatis" readonly>
                            {{-- Hidden input holds the actual numeric value for submission, pre-filled --}}
                            <input type="hidden" name="total_harga" id="total_harga_numeric"
                                value="{{ old('total_harga', $pemesanan->total_harga) }}">
                            @error('total_harga')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror

                            {{-- PRICE BREAKDOWN SECTION --}}
                            <div id="price-breakdown" class="mt-2 text-sm text-gray-600 border-t pt-2 hidden">
                                <h4 class="font-semibold mb-1 text-gray-700">Rincian Perhitungan:</h4>
                                <div class="space-y-1">
                                    <div id="breakdown-item-service"><span class="font-medium">Layanan (<span
                                                id="breakdown-service-name">-</span>):</span><span class="float-right"
                                            id="breakdown-service-total">Rp 0</span><span
                                            class="block text-xs text-gray-500 clear-both"
                                            id="breakdown-service-calc-info"></span></div>
                                    <div id="breakdown-item-speed" style="display: none;"><span
                                            class="font-medium">Biaya Kecepatan (<span
                                                id="breakdown-speed-name">-</span>):</span><span class="float-right"
                                            id="breakdown-speed-cost">Rp 0</span></div>
                                    <div id="breakdown-item-method" style="display: none;"><span
                                            class="font-medium">Biaya Metode (<span
                                                id="breakdown-method-name">-</span>):</span><span class="float-right"
                                            id="breakdown-method-cost">Rp 0</span></div>
                                </div>
                            </div>
                            {{-- END PRICE BREAKDOWN SECTION --}}
                        </div>

                        {{-- Promo Code --}}
                        <div>
                            <label for="kode_promo" class="block text-sm font-medium text-gray-700 mb-1">Kode Promo
                                <span class="text-gray-500 text-xs">(Jika ada)</span></label>
                            {{-- Pre-fill value --}}
                            <input type="text" name="kode_promo" id="kode_promo"
                                value="{{ old('kode_promo', $pemesanan->kode_promo) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('kode_promo') border-red-500 @enderror"
                                placeholder="Masukkan kode promo">
                            @error('kode_promo')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Status & Completion Section --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Status & Penyelesaian</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        {{-- Order Status --}}
                        <div>
                            <label for="status_pesanan" class="block text-sm font-medium text-gray-700 mb-1">Status
                                Pesanan<span class="text-red-500 ml-1">*</span></label>
                            <select name="status_pesanan" id="status_pesanan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('status_pesanan') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Status --</option>
                                @foreach ($statuses ?? ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'] as $status)
                                    {{-- Pre-select based on existing data --}}
                                    <option value="{{ $status }}"
                                        {{ old('status_pesanan', $pemesanan->status_pesanan) == $status ? 'selected' : '' }}>
                                        {{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status_pesanan')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Completion Date --}}
                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Selesai <span class="text-gray-500 text-xs">(Otomatis/Manual)</span></label>
                            {{-- Pre-fill value (format datetime for input) --}}
                            <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $pemesanan->tanggal_selesai ? \Carbon\Carbon::parse($pemesanan->tanggal_selesai)->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_selesai') border-red-500 @enderror">
                            @error('tanggal_selesai')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>


                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row gap-4 mt-6 pt-6 border-t border-gray-200">
                    {{-- Changed Button Text --}}
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-semibold inline-flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd"
                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Update Pesanan</span> {{-- Changed Text --}}
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

    {{-- JavaScript - Identical to create.blade.php --}}
    <script>
        // --- Get Additional Costs Data from PHP ---
        const biayaTambahan = @json($biayaTambahan ?? ['kecepatan' => [], 'metode' => []]);
        let autoNumericInstance = null;

        // Helper function to format currency
        const formatCurrency = (value) => {
            /* ... same as before ... */
            const tempInput = document.createElement('input');
            tempInput.type = 'text';
            let formatted = 'Rp 0';
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
                console.error("Format Currency Error:", e);
                formatted = 'Rp ' + (value ? Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '0');
            } finally {
                if (tempInput.parentNode === document.body) document.body.removeChild(tempInput);
            }
            return formatted;
        };

        document.addEventListener("DOMContentLoaded", function() {
            // --- References to Form Elements ---
            const layananSelect = document.getElementById('layanan_utama_id');
            const kecepatanSelect = document.getElementById('kecepatan_layanan');
            const metodeSelect = document.getElementById('metode_layanan');
            const totalHargaDisplayInput = document.getElementById('total_harga');
            const totalHargaNumericInput = document.getElementById('total_harga_numeric');
            const beratFinalField = document.getElementById('berat_final_field');
            const beratFinalInput = document.getElementById('berat_final');
            const jumlahItemField = document.getElementById('jumlah_item_field');
            const jumlahItemInput = document.getElementById('jumlah_item_input');

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


            // --- Initialize AutoNumeric ---
            try {
                if (totalHargaDisplayInput && totalHargaNumericInput) {
                    autoNumericInstance = new AutoNumeric(totalHargaDisplayInput, {
                        currencySymbol: 'Rp ',
                        decimalCharacter: ',',
                        digitGroupSeparator: '.',
                        decimalPlaces: 0,
                        readOnly: true
                    });
                } else {
                    console.error("Price inputs not found.");
                }
            } catch (e) {
                console.error("AutoNumeric init failed:", e);
            }


            // --- Function to Toggle Weight/Quantity Inputs ---
            function toggleInputFieldsBasedOnServiceType() {
                console.log("Toggling input fields...");
                if (!layananSelect || !beratFinalField || !jumlahItemField) return;

                const selectedLayananOption = layananSelect.options[layananSelect.selectedIndex];
                const serviceName = selectedLayananOption?.text || '';
                let serviceType = 'satuan';

                if (serviceName && serviceName.toLowerCase().includes('kiloan')) {
                    serviceType = 'kiloan';
                }
                console.log("Determined Service Type:", serviceType);

                if (serviceType === 'kiloan') {
                    beratFinalField.style.display = 'block';
                    jumlahItemField.style.display = 'none';
                } else {
                    beratFinalField.style.display = 'none';
                    jumlahItemField.style.display = 'block';
                }
            }


            // --- Total Price Calculation Function ---
            function calculateTotalPrice() {
                console.log("--- Calculating Total Price ---");
                if (!layananSelect || !kecepatanSelect || !metodeSelect || !autoNumericInstance || !
                    totalHargaNumericInput || !breakdownDiv || !beratFinalInput || !jumlahItemInput || !
                    breakdownItemServiceDiv || !breakdownServiceNameEl || !breakdownServiceTotalEl || !
                    breakdownServiceCalcInfoEl || !breakdownItemSpeedDiv || !breakdownSpeedNameEl || !
                    breakdownSpeedCostEl || !breakdownItemMethodDiv || !breakdownMethodNameEl || !
                    breakdownMethodCostEl) {
                    console.warn("Calc skipped: elements missing.");
                    return;
                }
                const selectedLayananOption = layananSelect.options[layananSelect.selectedIndex];
                const basePrice = parseFloat(selectedLayananOption?.dataset.harga || 0);
                const serviceName = selectedLayananOption ? selectedLayananOption.text.split(' (Rp')[0].trim() : '';
                console.log(`Service: Name='${serviceName}', Base Price='${basePrice}'`);
                let serviceType = 'satuan';
                if (serviceName && serviceName.toLowerCase().includes('kiloan')) {
                    serviceType = 'kiloan';
                }
                console.log(`Service Type: ${serviceType}`);
                const beratFinal = parseFloat(beratFinalInput.value) || 0;
                let quantity = parseInt(jumlahItemInput.value);
                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                }
                console.log(`Weight: ${beratFinal}, Quantity: ${quantity}`);
                let calculatedPrice = 0;
                let serviceCalcInfoText = '';
                if (!selectedLayananOption || layananSelect.value === "") {
                    calculatedPrice = 0;
                    serviceCalcInfoText = 'Pilih layanan';
                } else if (serviceType === 'kiloan') {
                    calculatedPrice = basePrice * beratFinal;
                    serviceCalcInfoText =
                        `${formatCurrency(basePrice)}/kg × ${beratFinal.toLocaleString('id-ID')} kg`;
                } else {
                    calculatedPrice = basePrice * quantity;
                    serviceCalcInfoText = `${formatCurrency(basePrice)}/pcs × ${quantity} pcs`;
                }
                console.log(`Calculated Service Price: ${calculatedPrice}`);
                const selectedKecepatan = kecepatanSelect.value;
                const biayaKecepatan = biayaTambahan.kecepatan[selectedKecepatan] || 0;
                console.log(`Speed: '${selectedKecepatan}', Cost: ${biayaKecepatan}`);
                const selectedMetode = metodeSelect.value;
                const biayaMetode = biayaTambahan.metode[selectedMetode] || 0;
                console.log(`Method: '${selectedMetode}', Cost: ${biayaMetode}`);
                const finalPrice = calculatedPrice + biayaKecepatan + biayaMetode;
                console.log(`Final Price: ${finalPrice}`);
                autoNumericInstance.set(finalPrice);
                totalHargaNumericInput.value = finalPrice;
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
                }
                console.log("--- Calculation End ---");
            }

            // --- Event Listeners ---
            layananSelect?.addEventListener('change', () => {
                console.log("Layanan changed");
                toggleInputFieldsBasedOnServiceType();
                calculateTotalPrice();
            });
            kecepatanSelect?.addEventListener('change', calculateTotalPrice);
            metodeSelect?.addEventListener('change', calculateTotalPrice);
            beratFinalInput?.addEventListener('input', calculateTotalPrice);
            jumlahItemInput?.addEventListener('input', calculateTotalPrice);


            // --- Initial Setup on Load ---
            console.log("Running initial setup on DOMContentLoaded for EDIT page");
            toggleInputFieldsBasedOnServiceType();
            calculateTotalPrice();


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

            function toggleConditionalFields() {
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
