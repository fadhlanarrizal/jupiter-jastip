<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pemesanan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm whitespace-nowrap">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-bold">#</th>
                            <th class="px-4 py-2 text-left">Nama Pemesan</th>
                            <th class="px-4 py-2 text-left">Barang</th>
                            <th class="px-4 py-2 text-left">Jenis</th>
                            <th class="px-4 py-2 text-left">Harga</th>
                            <th class="px-4 py-2 text-left">Berat</th>
                            <th class="px-4 py-2 text-left">Jumlah</th>
                            <th class="px-4 py-2 text-left">Catatan</th>
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
                                    {{ $order->nama_pemesan }}<br>
                                    <span class="text-xs text-gray-500">{{ $order->no_hp }}</span>
                                </td>
                                <td class="px-4 py-2">{{ $order->nama_barang }}</td>
                                <td class="px-4 py-2 capitalize">{{ $order->jenis }}</td>
                                <td class="px-4 py-2">Rp{{ number_format($order->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $order->berat ?? '-' }} kg</td>
                                <td class="px-4 py-2">{{ $order->jumlah ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $order->catatan ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    Rp{{ number_format(
                                        in_array($order->jenis, ['camilan-kiloan']) 
                                            ? $order->harga * $order->berat 
                                            : $order->harga * $order->jumlah, 
                                        0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">Rp{{ number_format($order->biaya, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 font-bold">
                                    Rp{{ number_format(
                                        (in_array($order->jenis, ['camilan-kiloan']) 
                                            ? $order->harga * $order->berat 
                                            : $order->harga * $order->jumlah) + $order->biaya, 
                                        0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $statusClass = match ($order->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'lunas' => 'bg-green-100 text-green-800',
                                            'pengiriman' => 'bg-blue-100 text-blue-800',
                                            'diterima' => 'bg-purple-100 text-purple-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
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
    </div>
</x-app-layout>
