<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pemesanan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow rounded-lg p-6">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-bold">#</th>
                        <th class="px-4 py-2 text-left">Nama Pemesan</th>
                        <th class="px-4 py-2 text-left">Barang</th>
                        <th class="px-4 py-2 text-left">Jenis</th>
                        <th class="px-4 py-2 text-left">Harga</th>
                        <th class="px-4 py-2 text-left">Berat</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Jastip</th>
                        <th class="px-4 py-2 text-left">Total Keseluruhan</th>
                        <th class="px-4 py-2 text-left">Status</th> 
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">
                                {{ $order->nama_pemesan }} <br>
                                <span class="text-xs text-gray-500">{{ $order->no_hp }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $order->nama_barang }}</td>
                            <td class="px-4 py-2 capitalize">{{ $order->jenis }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($order->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $order->berat ?? '-' }} kg</td>
                            <td class="px-4 py-2">{{ $order->jumlah ?? '-' }}</td>
                            <td class="px-4 py-2">
                                Rp{{ number_format($order->jenis === 'camilan' ? $order->harga * $order->berat : $order->harga * $order->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2">Rp{{ number_format($order->biaya, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 font-bold">
                                Rp{{ number_format(
                                    ($order->jenis === 'camilan' ? $order->harga * $order->berat : $order->harga * $order->jumlah) 
                                    + $order->biaya, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 capitalize font-semibold">
                                {{ $order->status }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('pesan.edit', $order->id) }}" class="text-blue-600 hover:underline text-sm">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($orders->isEmpty())
                <p class="text-center text-gray-500 py-6">Belum ada data pemesanan.</p>
            @endif
        </div>
    </div>
</x-app-layout>
