{{-- resources/views/guest/order_form.blade.php --}}
<x-guest-layout>
    <div class="w-full px-4">

        <h2 class="text-[3rem] font-semibold text-center text-gray-800 mb-6">
            Buat Pesanan Laundry Anda
        </h2>

        {{-- HAPUS BAGIAN INI: Pesan Sukses Standar
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700" role="alert">
                <span class="font-bold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif
        --}}

        {{-- Menampilkan Pesan Error Umum (Biarkan ini) --}}
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <span class="font-bold">Oops!</span> {{ session('error') }}
            </div>
        @endif

        {{-- Menampilkan Error Validasi Umum (Biarkan ini) --}}
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                <span class="font-medium">Terdapat kesalahan pada input Anda:</span>
                <ul class="mt-1.5 ml-4 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('guest.order.store') }}" class="space-y-5">
            @csrf

            <!-- Nama Pelanggan -->
            <div>
                <label for="nama_pelanggan" class="block text-base font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_pelanggan" id="nama_pelanggan" required
                    class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('nama_pelanggan') border-red-500 @enderror"
                    value="{{ old('nama_pelanggan') }}" placeholder="Masukkan nama lengkap Anda">
                @error('nama_pelanggan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kontak -->
            <div>
                <label for="kontak" class="block text-base font-medium text-gray-700 mb-1">Nomor Telepon /
                    WhatsApp</label>
                <input type="tel" name="kontak" id="kontak" required
                    class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('kontak') border-red-500 @enderror"
                    value="{{ old('kontak') }}" placeholder="Contoh: 081234567890">
                @error('kontak')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-base font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="3" required
                    class="block w-full rounded-md placeholder-gray-400 border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('alamat') border-red-500 @enderror"
                    placeholder="Masukkan alamat lengkap (jalan, nomor rumah, RT/RW, kelurahan, kecamatan, dll)">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Layanan -->
            <div>
                <label for="layanan_id" class="block text-base font-medium text-gray-700 mb-1">Pilih Layanan</label>
                <select name="layanan_id" id="layanan_id" required
                    class="block w-full rounded-md  border-gray-300 shadow-sm focus:border-[#4769e4] focus:ring-[#4769e4] sm:text-sm @error('layanan_id') border-red-500 @enderror">
                    <option value="" disabled {{ old('layanan_id') ? '' : 'selected' }}>-- Pilih Jenis Layanan --
                    </option>
                    @forelse ($layanans as $layanan)
                        {{-- Pastikan $layanan adalah objek atau array yang memiliki properti/key 'id', 'nama_layanan', dan 'harga' --}}
                        @if (isset($layanan->id) && isset($layanan->nama_layanan) && isset($layanan->harga))
                            <option value="{{ $layanan->id }}"
                                {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }}
                                {{-- Menampilkan harga yang diformat --}}
                                (Rp {{ number_format($layanan->harga, 0, ',', '.') }} / Kg)
                                {{-- CATATAN: Asumsi satuan adalah 'Kg'. Jika ada layanan non-kiloan, idealnya tambahkan kolom 'satuan' di DB Layanan --}}
                            </option>
                        @endif
                    @empty
                        <option value="" disabled>Tidak ada layanan tersedia</option>
                    @endforelse
                </select>
                @error('layanan_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                {{-- Anda bisa menambahkan deskripsi layanan di sini jika diperlukan --}}
                {{-- <p class="mt-1 text-xs text-gray-500">Deskripsi singkat layanan...</p> --}}
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full inline-flex justify-center py-2.5 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#4769e4] hover:bg-[#3a58c4] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4769e4] transition duration-150 ease-in-out">
                    Kirim Pesanan
                </button>
            </div>
        </form>
    </div>

    {{-- ======================================================= --}}
    {{-- ============ TAMBAHKAN SCRIPT SWEETALERT DI SINI ======== --}}
    {{-- ======================================================= --}}
    <script>
        // Jalankan script setelah halaman siap
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada session data dari controller untuk SweetAlert
            @if (session('swal_success_message') && session('order_number'))
                const successMessage = @json(session('swal_success_message')); // Ambil pesan dari session
                const orderNumber = @json(session('order_number')); // Ambil nomor pesanan dari session

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    // Gunakan `html` untuk memformat konten dengan tombol copy
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
                    confirmButtonColor: '#3085d6', // Warna tombol OK
                    // Fungsi yang dijalankan setelah alert muncul
                    didOpen: () => {
                        const copyBtn = document.getElementById('copyOrderNumberBtn');
                        const numberText = document.getElementById('orderNumberText')
                            .innerText; // Ambil teks nomor pesanan

                        if (copyBtn && navigator
                            .clipboard) { // Pastikan tombol ada dan clipboard API didukung
                            copyBtn.addEventListener('click', () => {
                                navigator.clipboard.writeText(numberText).then(() => {
                                    // Feedback bahwa teks berhasil disalin
                                    const originalButtonHtml = copyBtn.innerHTML;
                                    copyBtn.innerHTML = `
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tersalin!
                                    `;
                                    copyBtn.disabled =
                                        true; // Nonaktifkan tombol sementara

                                    // Kembalikan teks tombol setelah beberapa saat
                                    setTimeout(() => {
                                        copyBtn.innerHTML = originalButtonHtml;
                                        copyBtn.disabled = false;
                                    }, 2000); // Kembalikan setelah 2 detik

                                }).catch(err => {
                                    console.error('Gagal menyalin nomor pesanan: ',
                                        err);
                                    // Anda bisa menambahkan alert fallback jika copy gagal
                                    alert(
                                        'Gagal menyalin nomor pesanan. Silakan salin manual.'
                                    );
                                });
                            });
                        } else if (copyBtn) {
                            // Fallback jika clipboard API tidak didukung (browser lama)
                            copyBtn.style.display = 'none'; // Sembunyikan tombol copy
                        }
                    }
                });
            @endif
        });
    </script>
    {{-- ======================================================= --}}
    {{-- ============ AKHIR SCRIPT SWEETALERT ================== --}}
    {{-- ======================================================= --}}

</x-guest-layout>
