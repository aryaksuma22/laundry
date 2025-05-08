<x-app-layout>
    <div class="py-8 px-4 md:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">Tambah Data Transaksi</h2>

            {{-- Validation Error Display --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops! Ada kesalahan:</strong>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        {{-- Tampilkan info jika transaksi sudah ada --}}
                        @if ($errors->has('pemesanan_id') && Str::contains($errors->first('pemesanan_id'), 'sudah memiliki data transaksi'))
                            <li><a href="{{ route('transaksis.edit', $existingTransaksi->id ?? '#') }}"
                                    class="font-semibold underline hover:text-red-900">Klik di sini untuk mengedit
                                    transaksi yang sudah ada.</a></li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- Info jika transaksi sudah lunas --}}
            @if (isset($existingTransaksi) && $existingTransaksi->isLunas())
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Informasi:</strong> Pesanan ini sudah Lunas. Anda masih bisa mengedit
                    transaksi jika diperlukan.
                    <a href="{{ route('transaksis.edit', $existingTransaksi->id) }}"
                        class="font-semibold underline hover:text-green-900 ml-2">Edit Transaksi</a>
                </div>
            @endif

            <form action="{{ route('transaksis.store') }}" method="POST"
                class="bg-white shadow-md rounded-lg p-6 md:p-8 space-y-6">
                @csrf

                {{-- Pemesanan Information Section --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Informasi Pemesanan</legend>
                    <div class="mt-4 space-y-4">
                        @if ($pemesanan) {{-- Jika pemesanan_id di-pass dari URL --}}
                            <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">No Pesanan</span>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $pemesanan->no_pesanan }}</p>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Pelanggan</span>
                                    <p class="mt-1 text-gray-800">{{ $pemesanan->nama_pelanggan }}</p>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Total Tagihan</span>
                                    <p class="mt-1 text-lg font-bold text-indigo-600">Rp
                                        {{ number_format($pemesanan->total_harga ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            {{-- Tampilkan info pembayaran sebelumnya jika ada --}}
                            @if ($existingTransaksi)
                                <div
                                    class="p-3 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                                    Pesanan ini sudah memiliki catatan pembayaran sebelumnya (Status:
                                    {{ $existingTransaksi->status_pembayaran }}, Dibayar: Rp
                                    {{ number_format($existingTransaksi->jumlah_dibayar, 0, ',', '.') }}). Melanjutkan
                                    akan membuat data transaksi baru (jika belum ada) atau gagal jika sudah ada.
                                    Sebaiknya <a href="{{ route('transaksis.edit', $existingTransaksi->id) }}"
                                        class="font-semibold underline hover:text-yellow-900">edit transaksi yang
                                        ada</a>.
                                </div>
                            @endif
                        @else
                            {{-- Jika tidak ada pemesanan_id, tampilkan dropdown --}}
                            <div>
                                <label for="pemesanan_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih
                                    Pemesanan<span class="text-red-500 ml-1">*</span></label>
                                <select name="pemesanan_id" id="pemesanan_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('pemesanan_id') border-red-500 @enderror"
                                    required>
                                    <option value="">-- Pilih No Pesanan / Pelanggan --</option>
                                    @foreach ($pemesanansBelumLunas ?? [] as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('pemesanan_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->no_pesanan }} - {{ $item->nama_pelanggan }} (Rp
                                            {{ number_format($item->total_harga ?? 0, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pemesanan_id')
                                    <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Hanya menampilkan pesanan yang belum lunas.</p>
                            </div>
                        @endif
                    </div>
                </fieldset>

                {{-- Transaction Details Section --}}
                <fieldset class="border rounded-md p-4 shadow-sm">
                    <legend class="text-lg font-semibold text-gray-700 px-2">Detail Pembayaran</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        {{-- No Invoice --}}
                        <div>
                            <label for="no_invoice" class="block text-sm font-medium text-gray-700 mb-1">No Invoice
                                <span class="text-gray-500 text-xs">(Opsional)</span></label>
                            <input type="text" name="no_invoice" id="no_invoice"
                                value="{{ old('no_invoice', $pemesanan->no_pesanan ?? '') }}" {{-- Suggestion --}}
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('no_invoice') border-red-500 @enderror"
                                placeholder="Otomatis/Manual">
                            @error('no_invoice')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jumlah Dibayar --}}
                        <div>
                            <label for="jumlah_dibayar_display"
                                class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dibayar<span
                                    class="text-red-500 ml-1">*</span></label>
                            <input type="text" id="jumlah_dibayar_display" {{-- ID untuk AutoNumeric --}}
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('jumlah_dibayar') border-red-500 @enderror"
                                placeholder="Masukkan jumlah pembayaran">
                            <input type="hidden" name="jumlah_dibayar" id="jumlah_dibayar_numeric"
                                value="{{ old('jumlah_dibayar', 0) }}">
                            @error('jumlah_dibayar')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Pembayaran --}}
                        <div>
                            <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Status
                                Pembayaran<span class="text-red-500 ml-1">*</span></label>
                            <select name="status_pembayaran" id="status_pembayaran"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('status_pembayaran') border-red-500 @enderror"
                                required>
                                {{-- Opsi default bisa 'Belum Lunas' --}}
                                @foreach ($statusPembayaranOptions ?? ['Belum Lunas', 'Lunas'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status_pembayaran', 'Belum Lunas') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_pembayaran')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Metode Pembayaran (Conditional required) --}}
                        <div id="metode_pembayaran_field">
                            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Metode
                                Pembayaran<span id="metode_required_indicator"
                                    class="text-red-500 ml-1 hidden">*</span></label>
                            <select name="metode_pembayaran" id="metode_pembayaran"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('metode_pembayaran') border-red-500 @enderror">
                                <option value="">-- Pilih Metode --</option>
                                @foreach ($metodePembayaranOptions ?? [] as $metode)
                                    <option value="{{ $metode }}"
                                        {{ old('metode_pembayaran') == $metode ? 'selected' : '' }}>
                                        {{ $metode }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Pembayaran (Conditional required) --}}
                        <div id="tanggal_pembayaran_field">
                            <label for="tanggal_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Pembayaran<span id="tanggal_required_indicator"
                                    class="text-red-500 ml-1 hidden">*</span></label>
                            <input type="datetime-local" name="tanggal_pembayaran" id="tanggal_pembayaran"
                                value="{{ old('tanggal_pembayaran') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_pembayaran') border-red-500 @enderror">
                            @error('tanggal_pembayaran')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan Transaksi --}}
                        <div class="md:col-span-2">
                            <label for="catatan_transaksi" class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                Transaksi <span class="text-gray-500 text-xs">(Opsional)</span></label>
                            <textarea name="catatan_transaksi" id="catatan_transaksi" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('catatan_transaksi') border-red-500 @enderror"
                                placeholder="Info transfer, nama kasir, dll">{{ old('catatan_transaksi') }}</textarea>
                            @error('catatan_transaksi')
                                <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row gap-4 mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 font-semibold inline-flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Simpan Transaksi</span>
                    </button>
                    <a href="{{ route('transaksis.index') }}"
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
        document.addEventListener("DOMContentLoaded", function() {
            const statusPembayaranSelect = document.getElementById('status_pembayaran');
            const metodePembayaranField = document.getElementById('metode_pembayaran_field');
            const metodePembayaranSelect = document.getElementById('metode_pembayaran');
            const metodeRequiredIndicator = document.getElementById('metode_required_indicator');
            const tanggalPembayaranField = document.getElementById('tanggal_pembayaran_field');
            const tanggalPembayaranInput = document.getElementById('tanggal_pembayaran');
            const tanggalRequiredIndicator = document.getElementById('tanggal_required_indicator');

            // --- AutoNumeric for Jumlah Dibayar ---
            let autoNumericJumlahDibayar = null;
            const jumlahDibayarDisplay = document.getElementById('jumlah_dibayar_display');
            const jumlahDibayarNumeric = document.getElementById('jumlah_dibayar_numeric');

            if (jumlahDibayarDisplay && jumlahDibayarNumeric) {
                try {
                    autoNumericJumlahDibayar = new AutoNumeric(jumlahDibayarDisplay, {
                        currencySymbol: 'Rp ',
                        decimalCharacter: ',',
                        digitGroupSeparator: '.',
                        decimalPlaces: 0,
                        minimumValue: 0
                    });

                    // Set initial value from old input or 0
                    autoNumericJumlahDibayar.set(jumlahDibayarNumeric.value || 0);

                    // Update hidden field on change
                    jumlahDibayarDisplay.addEventListener('autoNumeric:rawValueModified', (event) => {
                        jumlahDibayarNumeric.value = event.detail.newRawValue;
                    });
                } catch (e) {
                    console.error("AutoNumeric init failed for jumlah_dibayar:", e);
                }
            }
            // --- End AutoNumeric ---


            function toggleConditionalFields() {
                if (!statusPembayaranSelect || !metodePembayaranField || !tanggalPembayaranField || !
                    metodeRequiredIndicator || !tanggalRequiredIndicator) return;

                const isLunas = statusPembayaranSelect.value === 'Lunas';

                metodePembayaranField.style.display = 'block'; // Always show field
                tanggalPembayaranField.style.display = 'block'; // Always show field

                // Toggle required indicator visibility
                metodeRequiredIndicator.classList.toggle('hidden', !isLunas);
                tanggalRequiredIndicator.classList.toggle('hidden', !isLunas);

                // Optional: Add/remove 'required' attribute dynamically (though backend validation is primary)
                if (metodePembayaranSelect) metodePembayaranSelect.required = isLunas;
                if (tanggalPembayaranInput) tanggalPembayaranInput.required = isLunas;

                // Auto-fill tanggal bayar jika status LUNAS dan tanggal kosong
                if (isLunas && tanggalPembayaranInput && !tanggalPembayaranInput.value) {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // Adjust for local timezone
                    tanggalPembayaranInput.value = now.toISOString().slice(0, 16);
                }
            }

            if (statusPembayaranSelect) {
                toggleConditionalFields(); // Initial check
                statusPembayaranSelect.addEventListener('change', toggleConditionalFields);
            }
        });
    </script>

</x-app-layout>
