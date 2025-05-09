{{-- resources/views/guest/order_form.blade.php --}}
<x-guest-layout>
    <div class="w-full max-w-4xl mx-auto px-4 py-2 md:py-6">

        <h2 class="text-2xl md:text-3xl font-semibold text-center text-gray-800 mb-6 md:mb-8">
            Buat Pesanan Laundry Anda
        </h2>

        {{-- Error and Validation Messages --}}
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <span class="font-bold">Oops!</span> {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                <span class="font-medium">Terdapat kesalahan pada input Anda:</span>
                <ul class="mt-1.5 ml-4 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Start --}}
        <form method="POST" action="{{ route('guest.order.store') }}"
            class="space-y-5 bg-white p-6 sm:p-8 rounded-lg shadow-xl border">
            @csrf

            {{-- Section 1: Informasi Pelanggan (No Change) --}}
            <fieldset>
                <legend class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">1. Informasi Anda</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" required
                            class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('nama_pelanggan') border-red-500 @enderror"
                            value="{{ old('nama_pelanggan') }}" placeholder="Masukkan nama lengkap Anda">
                        @error('nama_pelanggan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kontak_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon
                            / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="tel" name="kontak_pelanggan" id="kontak_pelanggan" required
                            class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('kontak_pelanggan') border-red-500 @enderror"
                            value="{{ old('kontak_pelanggan') }}" placeholder="Contoh: 081234567890">
                        @error('kontak_pelanggan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Section 2: Metode Layanan & Alamat (No Change to HTML structure) --}}
            <fieldset>
                <legend class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">2. Metode & Alamat</legend>
                <div class="space-y-3">
                    <div>
                        <label for="metode_layanan" class="block text-sm font-medium text-gray-700 mb-1">Metode Layanan
                            <span class="text-red-500">*</span></label>
                        <select name="metode_layanan" id="metode_layanan" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('metode_layanan') border-red-500 @enderror">
                            <option value="">-- Pilih Metode Layanan --</option>
                            <option value="Datang Langsung"
                                {{ old('metode_layanan') == 'Datang Langsung' ? 'selected' : '' }}>Saya Antar & Ambil Sendiri</option>
                            <option value="Antar Jemput"
                                {{ old('metode_layanan') == 'Antar Jemput' ? 'selected' : '' }}>Minta Dijemput & Diantar</option>
                            <option value="Antar Sendiri Minta Diantar"
                                {{ old('metode_layanan') == 'Antar Sendiri Minta Diantar' ? 'selected' : '' }}>Saya Antar, Minta Diantar</option>
                            <option value="Minta Dijemput Ambil Sendiri"
                                {{ old('metode_layanan') == 'Minta Dijemput Ambil Sendiri' ? 'selected' : '' }}>Minta Dijemput, Saya Ambil Sendiri</option>
                        </select>
                         @error('metode_layanan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div id="alamat_section" class="hidden">
                        <label for="alamat_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Alamat
                            Lengkap <span id="alamat_required_star" class="text-red-500 hidden">*</span></label>
                        <textarea name="alamat_pelanggan" id="alamat_pelanggan" rows="2"
                            class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('alamat_pelanggan') border-red-500 @enderror"
                            placeholder="Jalan, No Rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kab, Kodepos">{{ old('alamat_pelanggan') }}</textarea>
                        @error('alamat_pelanggan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div id="penjemputan_section" class="hidden grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                        <div>
                            <label for="tanggal_penjemputan" class="block text-sm font-medium text-gray-700 mb-1">Tgl Jemput <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_penjemputan" id="tanggal_penjemputan" min="{{ date('Y-m-d') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('tanggal_penjemputan') border-red-500 @enderror"
                                value="{{ old('tanggal_penjemputan') }}">
                            @error('tanggal_penjemputan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="waktu_penjemputan" class="block text-sm font-medium text-gray-700 mb-1">Waktu Jemput <span class="text-red-500">*</span></label>
                            <select name="waktu_penjemputan" id="waktu_penjemputan"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('waktu_penjemputan') border-red-500 @enderror">
                                <option value="">-- Pilih Slot --</option>
                                <option value="Pagi (09:00 - 12:00)" {{ old('waktu_penjemputan') == 'Pagi (09:00 - 12:00)' ? 'selected' : '' }}>Pagi (09-12)</option>
                                <option value="Siang (13:00 - 16:00)" {{ old('waktu_penjemputan') == 'Siang (13:00 - 16:00)' ? 'selected' : '' }}>Siang (13-16)</option>
                                <option value="Sore (16:00 - 18:00)" {{ old('waktu_penjemputan') == 'Sore (16:00 - 18:00)' ? 'selected' : '' }}>Sore (16-18)</option>
                            </select>
                            @error('waktu_penjemputan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div id="instruksi_alamat_section" class="hidden">
                        <label for="instruksi_alamat" class="block text-sm font-medium text-gray-700 mb-1">Instruksi Alamat/Jemput</label>
                        <textarea name="instruksi_alamat" id="instruksi_alamat" rows="2"
                            class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('instruksi_alamat') border-red-500 @enderror"
                            placeholder="Patokan, pesan untuk kurir, dll.">{{ old('instruksi_alamat') }}</textarea>
                        @error('instruksi_alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Section 3: Detail Layanan (data-harga and data-type attributes are key) --}}
            <fieldset>
                <legend class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">3. Detail Layanan</legend>
                <div class="space-y-3">
                    <div>
                        <label for="layanan_utama_id" class="block text-sm font-medium text-gray-700 mb-1">Layanan Utama <span class="text-red-500">*</span></label>
                        <select name="layanan_utama_id" id="layanan_utama_id" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('layanan_utama_id') border-red-500 @enderror">
                            <option value="" data-harga="0" data-type="" disabled {{ old('layanan_utama_id') ? '' : 'selected' }}>-- Pilih Jenis Layanan --</option>
                            @forelse ($layanans as $layanan)
                                @if (isset($layanan->id) && isset($layanan->nama_layanan))
                                    @php
                                        $isKiloan = \Illuminate\Support\Str::contains(strtolower($layanan->nama_layanan), 'kiloan');
                                        $dataType = $isKiloan ? 'kiloan' : 'satuan';
                                        $priceUnit = $isKiloan ? 'Kg' : 'Pcs';
                                        $hargaLayanan = $layanan->harga ?? 0;
                                    @endphp
                                    <option value="{{ $layanan->id }}" data-type="{{ $dataType }}" data-harga="{{ $hargaLayanan }}"
                                        {{ old('layanan_utama_id') == $layanan->id ? 'selected' : '' }}>
                                        {{ $layanan->nama_layanan }}
                                        @if ($hargaLayanan > 0)
                                            (Rp {{ number_format($hargaLayanan, 0, ',', '.') }} / {{ $priceUnit }})
                                        @endif
                                    </option>
                                @endif
                            @empty
                                <option value="" data-harga="0" data-type="" disabled>Tidak ada layanan tersedia</option>
                            @endforelse
                        </select>
                        @error('layanan_utama_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                        <div id="estimasi_berat_section" class="hidden">
                            <label for="estimasi_berat" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Berat (Kg)</label>
                            <input type="number" step="0.1" min="0" name="estimasi_berat" id="estimasi_berat"
                                class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('estimasi_berat') border-red-500 @enderror"
                                value="{{ old('estimasi_berat') }}" placeholder="Contoh: 2.5">
                            @error('estimasi_berat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div id="daftar_item_section" class="hidden md:col-span-1"> {{-- Adjusted colspan if estimasi_berat takes one col --}}
                            <label for="daftar_item" class="block text-sm font-medium text-gray-700 mb-1">Daftar Item (layanan satuan)</label>
                            <textarea name="daftar_item" id="daftar_item" rows="3"
                                class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('daftar_item') border-red-500 @enderror"
                                placeholder="Contoh: 3 Kemeja, 2 Celana">{{ old('daftar_item') }}</textarea>
                            @error('daftar_item') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- Section 4: Preferensi Tambahan (No Change) --}}
            <fieldset>
                <legend class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">4. Preferensi Tambahan</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <label for="kecepatan_layanan" class="block text-sm font-medium text-gray-700 mb-1">Kecepatan <span class="text-red-500">*</span></label>
                        <select name="kecepatan_layanan" id="kecepatan_layanan" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('kecepatan_layanan') border-red-500 @enderror">
                            <option value="Reguler" {{ old('kecepatan_layanan', 'Reguler') == 'Reguler' ? 'selected' : '' }}>Reguler (Normal)</option>
                            <option value="Express" {{ old('kecepatan_layanan') == 'Express' ? 'selected' : '' }}>Express</option>
                            <option value="Kilat" {{ old('kecepatan_layanan') == 'Kilat' ? 'selected' : '' }}>Kilat</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Dapat mempengaruhi harga.</p>
                        @error('kecepatan_layanan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="kode_promo" class="block text-sm font-medium text-gray-700 mb-1">Kode Promo / Voucher</label>
                        <input type="text" name="kode_promo" id="kode_promo"
                            class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('kode_promo') border-red-500 @enderror"
                            value="{{ old('kode_promo') }}" placeholder="Masukkan kode promo">
                        @error('kode_promo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="catatan_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Instruksi Khusus / Catatan</label>
                        <textarea name="catatan_pelanggan" id="catatan_pelanggan" rows="2"
                            class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('catatan_pelanggan') border-red-500 @enderror"
                            placeholder="Misal: Jangan pakai pewangi, pisahkan baju putih, noda di kemeja X, dll.">{{ old('catatan_pelanggan') }}</textarea>
                        @error('catatan_pelanggan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Estimasi Harga Section (No Change) --}}
            <div id="estimasi_harga_section" class="mt-6 pt-4 border-t">
                <h3 class="text-md font-semibold text-gray-700 mb-2">Estimasi Total Harga:</h3>
                <p id="estimasi_harga_display" class="text-xl md:text-2xl font-bold text-[#4769e4]">Rp 0</p>
                <p class="text-xs text-gray-500 mt-1">
                    Ini adalah estimasi berdasarkan pilihan Anda. Harga final akan dikonfirmasi oleh admin setelah pengecekan.
                </p>
            </div>

            {{-- Submit Button (No Change) --}}
            <div class="pt-3 md:pt-4">
                <button type="submit"
                    class="w-full inline-flex justify-center py-2.5 md:py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-[#4769e4] hover:bg-[#3a58c4] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4769e4] transition duration-150 ease-in-out">
                    Kirim Pesanan
                </button>
            </div>
        </form> {{-- Form End --}}
    </div>

    {{-- Data Biaya Tambahan untuk JavaScript (PENTING: SEBELUM script eksternal) --}}
    <script>
        const BIAYA_TAMBAHAN = @json($biayaTambahan ?? ['kecepatan' => [], 'metode' => []]);
    </script>

    {{-- Memuat file JavaScript eksternal --}}
    {{-- Jika menggunakan Vite: @vite('resources/js/guest_order_form.js') --}}
    {{-- Jika manual di public: <script src="{{ asset('js/guest_order_form.js') }}" defer></script> --}}
    <script src="{{ asset('js/guest_order_form.js') }}" defer></script>


    {{-- SweetAlert Script (Keep as is, atau bisa juga dipindah jika semua halaman guest membutuhkannya) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('swal_success_message') && session('order_number'))
                const successMessage = @json(session('swal_success_message'));
                const orderNumber = @json(session('order_number'));

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `
                        <div class="text-gray-700">${successMessage}</div>
                        <div class="mt-3 text-gray-600 text-sm">Nomor Pesanan Anda (mohon disimpan):</div>
                        <div class="mt-1 mb-4 p-2 bg-gray-100 border rounded font-mono text-lg text-center shadow-inner text-gray-800">
                            <span id="orderNumberText">${orderNumber}</span>
                        </div>
                        <button id="copyOrderNumberBtn" class="px-4 py-1.5 bg-[#4769e4] text-white rounded hover:bg-[#3a58c4] text-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#4769e4] focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Salin Nomor
                        </button>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    didOpen: () => {
                        const copyBtn = document.getElementById('copyOrderNumberBtn');
                        const numberTextElement = document.getElementById('orderNumberText');
                        if (copyBtn && numberTextElement && navigator.clipboard) {
                            const numberText = numberTextElement.innerText;
                            copyBtn.addEventListener('click', () => {
                                navigator.clipboard.writeText(numberText).then(() => {
                                    const originalButtonHtml = copyBtn.innerHTML;
                                    copyBtn.innerHTML = `
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tersalin!
                                    `;
                                    copyBtn.disabled = true;
                                    setTimeout(() => {
                                        copyBtn.innerHTML = originalButtonHtml;
                                        copyBtn.disabled = false;
                                    }, 2000);
                                }).catch(err => {
                                    console.error('Gagal menyalin: ', err);
                                    alert(
                                    'Gagal menyalin nomor pesanan. Salin manual.');
                                });
                            });
                        } else if (copyBtn) {
                            copyBtn.style.display = 'none';
                        }
                    }
                });
            @endif
        });
    </script>
</x-guest-layout>
