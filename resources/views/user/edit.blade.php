<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pemesanan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white p-6 shadow rounded-lg">
            <form action="{{ route('pesan.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Pemesan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
                    <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan', $order->nama_pemesan) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <!-- No HP -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $order->no_hp) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <!-- Nama Barang -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang', $order->nama_barang) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <!-- Jenis -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                    <select name="jenis" id="jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        required>
                        <option value="camilan" {{ $order->jenis == 'camilan' ? 'selected' : '' }}>Camilan</option>
                        <option value="non-camilan" {{ $order->jenis == 'non-camilan' ? 'selected' : '' }}>Non-Camilan
                        </option>
                    </select>
                </div>

                <!-- Berat (untuk camilan) -->
                <div class="mb-4" id="berat-wrapper" style="{{ $order->jenis === 'camilan' ? '' : 'display: none' }}">
                    <label class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                    <input type="number" step="0.01" name="berat" id="berat"
                        value="{{ old('berat', $order->berat) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- Harga per kg camilan -->
                <div class="mb-4" id="harga-camilan-wrapper"
                    style="{{ $order->jenis === 'camilan' ? '' : 'display: none' }}">
                    <label class="block text-sm font-medium text-gray-700">Harga per Kg (Rp)</label>
                    <input type="number" name="harga" id="harga-camilan" value="{{ old('harga', $order->harga) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- Preview Total Harga Barang -->
                <div class="mb-2" id="harga-total-wrapper"
                    style="{{ $order->jenis === 'camilan' ? '' : 'display: none' }}">
                    <label class="block text-sm font-medium text-gray-700">Total Harga Barang</label>
                    <input type="text" id="total-harga-camilan"
                        class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300" readonly>
                </div>

                <!-- Preview Biaya Jastip -->
                <div class="mb-2" id="biaya-jastip-wrapper"
                    style="{{ $order->jenis === 'camilan' ? '' : 'display: none' }}">
                    <label class="block text-sm font-medium text-gray-700">Biaya Jastip</label>
                    <input type="text" id="biaya-jastip-camilan"
                        class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300" readonly>
                </div>

                <!-- Total Semua -->
                <div class="mb-4" id="total-semua-wrapper"
                    style="{{ $order->jenis === 'camilan' ? '' : 'display: none' }}">
                    <label class="block text-sm font-medium text-gray-700">Total Seluruhnya</label>
                    <input type="text" id="total-semua-camilan"
                        class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300" readonly>
                </div>


                <!-- Harga dan Jumlah (untuk non-camilan) -->
                <div id="non-camilan-fields" style="{{ $order->jenis === 'non-camilan' ? '' : 'display: none' }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Harga Satuan (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga', $order->harga) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', $order->jumlah) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ $order->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="pengiriman" {{ $order->status == 'pengiriman' ? 'selected' : '' }}>Pengiriman
                        </option>
                        <option value="diterima" {{ $order->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    </select>
                </div>

                <!-- Catatan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="catatan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('catatan', $order->catatan) }}</textarea>
                </div>

                <!-- Submit -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('pesan.riwayat') }}" class="text-gray-600 hover:underline">← Kembali</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const jenisSelect = document.getElementById('jenis');
        const beratWrapper = document.getElementById('berat-wrapper');
        const nonCamilanFields = document.getElementById('non-camilan-fields');
        const hargaCamilanWrapper = document.getElementById('harga-camilan-wrapper');
        const totalHargaWrapper = document.getElementById('harga-total-wrapper');
        const biayaJastipWrapper = document.getElementById('biaya-jastip-wrapper');
        const totalSemuaWrapper = document.getElementById('total-semua-wrapper');

        const beratInput = document.getElementById('berat');
        const hargaCamilanInput = document.getElementById('harga-camilan');
        const totalHargaInput = document.getElementById('total-harga-camilan');
        const biayaJastipInput = document.getElementById('biaya-jastip-camilan');
        const totalSemuaInput = document.getElementById('total-semua-camilan');

        function updateJenisView() {
            const isCamilan = jenisSelect.value === 'camilan';
            beratWrapper.style.display = isCamilan ? 'block' : 'none';
            hargaCamilanWrapper.style.display = isCamilan ? 'block' : 'none';
            totalHargaWrapper.style.display = isCamilan ? 'block' : 'none';
            biayaJastipWrapper.style.display = isCamilan ? 'block' : 'none';
            totalSemuaWrapper.style.display = isCamilan ? 'block' : 'none';
            nonCamilanFields.style.display = isCamilan ? 'none' : 'block';

            if (isCamilan) updateCamilanSummary();
        }

        function updateCamilanSummary() {
            const berat = parseFloat(beratInput.value) || 0;
            const hargaPerKg = parseFloat(hargaCamilanInput.value) || 0;
            const totalHarga = berat * hargaPerKg;
            const biayaJastip = berat * 30000;
            const totalAll = totalHarga + biayaJastip;

            totalHargaInput.value = `Rp${totalHarga.toLocaleString('id-ID')}`;
            biayaJastipInput.value = `Rp${biayaJastip.toLocaleString('id-ID')}`;
            totalSemuaInput.value = `Rp${totalAll.toLocaleString('id-ID')}`;
        }

        jenisSelect.addEventListener('change', updateJenisView);
        beratInput?.addEventListener('input', updateCamilanSummary);
        hargaCamilanInput?.addEventListener('input', updateCamilanSummary);

        // Inisialisasi saat halaman dimuat
        updateJenisView();
    </script>

</x-app-layout>
