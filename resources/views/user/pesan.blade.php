<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Form Pemesanan Jastip
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pesan.store') }}" method="POST" class="bg-white shadow rounded p-6"
                x-data="{
                    jenis: '{{ old('jenis', '') }}',
                    harga: parseFloat('{{ old('harga', 0) }}') || 0,
                    berat: parseFloat('{{ old('berat', 0) }}') || 0,
                    jumlah: parseInt('{{ old('jumlah', 0) }}') || 0,
                    status: '{{ old('status', 'pending') }}',
                    tarifCamilanPerKg: 30000,
                    get totalHarga() {
                        if (this.jenis === 'camilan-kiloan') {
                            return this.harga * this.berat;
                        } else if (this.jenis === 'camilan-satuan') {
                            return this.harga * this.jumlah;
                        } else if (this.jenis === 'non-camilan') {
                            return this.harga * this.jumlah;
                        }
                        return 0;
                    },
                    get totalJastip() {
                        if (this.jenis === 'camilan-kiloan') {
                            return this.tarifCamilanPerKg * this.berat;
                        } else if (this.jenis === 'camilan-satuan') {
                            return this.tarifCamilanPerKg * this.jumlah * (this.jumlah * this.berat);
                        } else if (this.jenis === 'non-camilan') {
                            if (this.totalHarga > 100000) {
                                return this.totalHarga * 0.2;
                            } else {
                                return this.totalHarga * 0.3;
                            }
                        }
                        return 0;
                    }
                }"
            >
                @csrf

                <x-input-text label="Nama Pemesan" name="nama_pemesan" required />
                <x-input-text label="No. HP" name="no_hp" required />
                <x-input-text label="Nama Barang" name="nama_barang" required />

                <!-- Jenis Barang -->
                <div class="mb-4">
                    <label for="jenis" class="block text-gray-700 font-medium mb-2">Jenis</label>
                    <select id="jenis" name="jenis" required class="w-full border rounded px-3 py-2" x-model="jenis">
                        <option value="" disabled>Pilih Jenis</option>
                        <option value="camilan-kiloan" {{ old('jenis') == 'camilan-kiloan' ? 'selected' : '' }}>Camilan Kiloan</option>
                        <option value="camilan-satuan" {{ old('jenis') == 'camilan-satuan' ? 'selected' : '' }}>Camilan Satuan</option>
                        <option value="non-camilan" {{ old('jenis') == 'non-camilan' ? 'selected' : '' }}>Non-Camilan</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                    <select id="status" name="status" required class="w-full border rounded px-3 py-2" x-model="status">
                        <option value="" disabled>Pilih Status</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="pengiriman" {{ old('status') == 'pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                        <option value="diterima" {{ old('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    </select>
                </div>

                <!-- Harga -->
                <x-input-number label="Harga (Rp)" name="harga" required x-model.number="harga" step="1000" />

                <!-- Berat (kg) untuk camilan kiloan & camilan satuan -->
                <template x-if="jenis === 'camilan-kiloan' || jenis === 'camilan-satuan'">
                    <x-input-number label="Berat (kg)" name="berat" step="0.01" required x-model.number="berat" />
                </template>

                <!-- Jumlah camilan untuk camilan satuan -->
                <template x-if="jenis === 'camilan-satuan'">
                    <x-input-number label="Jumlah Camilan" name="jumlah" required x-model.number="jumlah" />
                </template>

                <!-- Jumlah barang untuk non-camilan -->
                <template x-if="jenis === 'non-camilan'">
                    <x-input-number label="Jumlah" name="jumlah" required x-model.number="jumlah" />
                </template>

                <!-- Catatan -->
                <div class="mb-4">
                    <label for="catatan" class="block text-gray-700 font-medium mb-2">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="w-full border rounded px-3 py-2">{{ old('catatan') }}</textarea>
                </div>

                <!-- Total Harga -->
                <div class="mb-4 font-semibold text-lg">
                    Total Harga:
                    <span x-text="totalHarga.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })">Rp0</span>
                </div>

                <!-- Total Jastip -->
                <div class="mb-6 font-semibold text-lg text-blue-600">
                    Total Jastip:
                    <span x-text="totalJastip.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })">Rp0</span>
                </div>

                <!-- Total Keseluruhan -->
                <div class="mb-6 font-semibold text-lg text-green-700 border-t pt-3">
                    Total Keseluruhan:
                    <span x-text="(totalHarga + totalJastip).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })">Rp0</span>
                </div>

                <!-- Submit -->
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan Pesanan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
