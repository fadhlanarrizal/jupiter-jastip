<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pemesanan Jastip
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pesan.update', $order->id) }}" method="POST" class="bg-white shadow rounded p-6"
                x-data="{
                    jenis: '{{ old('jenis', $order->jenis) }}',
                    harga: parseFloat('{{ old('harga', $order->harga) }}') || 0,
                    berat: parseFloat('{{ old('berat', $order->berat) }}') || 0,
                    jumlah: parseInt('{{ old('jumlah', $order->jumlah) }}') || 0,
                    status: '{{ old('status', $order->status) }}',
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
                            return this.tarifCamilanPerKg * (this.jumlah * this.berat);
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
                @method('PUT')

                <x-input-text label="Nama Pemesan" name="nama_pemesan" :value="old('nama_pemesan', $order->nama_pemesan)" required />
                <x-input-text label="No. HP" name="no_hp" :value="old('no_hp', $order->no_hp)" required />
                <x-input-text label="Nama Barang" name="nama_barang" :value="old('nama_barang', $order->nama_barang)" required />

                <!-- Jenis Barang -->
                <div class="mb-4">
                    <label for="jenis" class="block text-gray-700 font-medium mb-2">Jenis</label>
                    <select id="jenis" name="jenis" required class="w-full border rounded px-3 py-2" x-model="jenis">
                        <option value="" disabled>Pilih Jenis</option>
                        <option value="camilan-kiloan" :selected="jenis === 'camilan-kiloan'">Camilan Kiloan</option>
                        <option value="camilan-satuan" :selected="jenis === 'camilan-satuan'">Camilan Satuan</option>
                        <option value="non-camilan" :selected="jenis === 'non-camilan'">Non-Camilan</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                    <select id="status" name="status" required class="w-full border rounded px-3 py-2" x-model="status">
                        <option value="" disabled>Pilih Status</option>
                        <option value="pending" :selected="status === 'pending'">Pending</option>
                        <option value="lunas" :selected="status === 'lunas'">Lunas</option>
                        <option value="pengiriman" :selected="status === 'pengiriman'">Pengiriman</option>
                        <option value="diterima" :selected="status === 'diterima'">Diterima</option>
                    </select>
                </div>

                <!-- Harga -->
                <x-input-number label="Harga (Rp)" name="harga" required x-model.number="harga" step="1000" :value="old('harga', $order->harga)" />

                <!-- Berat (kg) untuk camilan kiloan & camilan satuan -->
                <template x-if="jenis === 'camilan-kiloan' || jenis === 'camilan-satuan'">
                    <x-input-number label="Berat (kg)" name="berat" step="0.01" required x-model.number="berat" :value="old('berat', $order->berat)" />
                </template>

                <!-- Jumlah camilan untuk camilan satuan -->
                <template x-if="jenis === 'camilan-satuan'">
                    <x-input-number label="Jumlah Camilan" name="jumlah" required x-model.number="jumlah" :value="old('jumlah', $order->jumlah)" />
                </template>

                <!-- Jumlah barang untuk non-camilan -->
                <template x-if="jenis === 'non-camilan'">
                    <x-input-number label="Jumlah" name="jumlah" required x-model.number="jumlah" :value="old('jumlah', $order->jumlah)" />
                </template>

                <!-- Catatan -->
                <div class="mb-4">
                    <label for="catatan" class="block text-gray-700 font-medium mb-2">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="w-full border rounded px-3 py-2">{{ old('catatan', $order->catatan) }}</textarea>
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
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
