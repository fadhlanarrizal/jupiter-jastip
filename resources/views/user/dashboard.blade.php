<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Rangkuman Orderan') }}
        </h2>
    </x-slot>




    <div class="py-6">
        {{-- Floating Buttons --}}
        <div class="fixed bottom-6 right-6 flex flex-col items-end space-y-4 z-50">

            {{-- Button: Rekap Per Pemesan --}}
            <a href="#rekap-pemesan"
                class="flex items-center space-x-2 bg-indigo-700 hover:bg-indigo-800 text-white font-semibold px-4 py-2 rounded-full shadow-xl transition duration-300"
                title="Scroll ke Rekap Per Pemesan">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 7a3 3 0 11-6 0 3 3 0 016 0zM4 14a8 8 0 1112 0H4z" />
                </svg>
                <span class="hidden md:inline">Pemesan</span>
            </a>

            {{-- Button: Rekap Per Barang --}}
            <a href="#rekap-barang"
                class="flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-full shadow-xl transition duration-300"
                title="Scroll ke Rekap Per Barang">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a1 1 0 000 2h1v11a1 1 0 001 1h8a1 1 0 001-1V5h1a1 1 0 100-2H4zm3 2h6v10H7V5z" />
                </svg>
                <span class="hidden md:inline">Barang</span>
            </a>

        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter waktu --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard') }}" class="inline-flex space-x-2">
                    @php
                        $filters = ['week' => 'Minggu', 'month' => 'Bulan', 'quarter' => '3 Bulan', 'year' => 'Tahun'];
                    @endphp
                    @foreach ($filters as $key => $label)
                        <button type="submit" name="filter" value="{{ $key }}"
                            class="px-4 py-2 rounded @if ($filter == $key) bg-blue-600 text-white @else bg-gray-200 hover:bg-gray-300 @endif">
                            {{ $label }}
                        </button>
                    @endforeach
                </form>
            </div>

            {{-- ekspor pdf --}}
            <a href="{{ route('dashboard.export', ['filter' => request('filter')]) }}"
                class="mb-4 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded shadow transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Ekspor PDF
            </a>

            {{-- Rekap per Pemesan --}}
            <section id="rekap-pemesan" class="mb-10 bg-white shadow rounded p-4">
                <h2 class="text-xl font-semibold mb-4">Rekap Per Pemesan</h2>
                @foreach ($perPemesan as $pemesan)
                    <div class="mb-6 border rounded p-4 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-lg">{{ $pemesan['nama'] }}</h3>
                            <span class="text-gray-600">No HP: {{ $pemesan['no_hp'] }}</span>
                        </div>

                        <table class="w-full text-left text-sm border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-3 py-1">Barang</th>
                                    <th class="border border-gray-300 px-3 py-1">Jenis</th>
                                    <th class="border border-gray-300 px-3 py-1">Berat (kg)</th>
                                    <th class="border border-gray-300 px-3 py-1">Harga (Rp)</th>
                                    <th class="border border-gray-300 px-3 py-1">Jumlah</th>
                                    <th class="border border-gray-300 px-3 py-1">Total (Rp)</th>
                                    <th class="border border-gray-300 px-3 py-1">Jastip (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemesan['detail'] as $detail)
                                    <tr>
                                        <td class="border border-gray-300 px-3 py-1">{{ $detail['nama_barang'] }}</td>
                                        <td class="border border-gray-300 px-3 py-1 capitalize">{{ $detail['jenis'] }}
                                        </td>
                                        <td class="border border-gray-300 px-3 py-1">{{ $detail['berat'] ?? '-' }}</td>
                                        <td class="border border-gray-300 px-3 py-1">
                                            {{ number_format($detail['harga'], 0, ',', '.') }}</td>
                                        <td class="border border-gray-300 px-3 py-1">{{ $detail['jumlah'] ?? '-' }}
                                        </td>
                                        <td class="border border-gray-300 px-3 py-1">
                                            {{ number_format($detail['total'], 0, ',', '.') }}</td>
                                        <td class="border border-gray-300 px-3 py-1">
                                            {{ number_format($detail['jastip'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-2 font-semibold">
                            Total Belanja: Rp {{ number_format($pemesan['total_belanja'], 0, ',', '.') }}<br>
                            Total Jastip: Rp {{ number_format($pemesan['total_jastip'], 0, ',', '.') }}<br>
                            <span class="text-blue-600">Total Keseluruhan: Rp
                                {{ number_format($pemesan['total_semua'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </section>

            {{-- Rekap per Barang --}}
            <section id="rekap-barang" class="bg-white shadow rounded p-4 mt-6">
                <h2 class="text-xl font-semibold mb-4">Rekap Per Barang</h2>

                <table class="w-full text-left text-sm border-collapse border border-gray-300 rounded overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-3 py-1">Barang</th>
                            <th class="border border-gray-300 px-3 py-1">Jenis</th>
                            <th class="border border-gray-300 px-3 py-1">Total Berat (kg)</th>
                            <th class="border border-gray-300 px-3 py-1">Total Jumlah</th>
                            <th class="border border-gray-300 px-3 py-1">Total Harga (Rp)</th>
                            <th class="border border-gray-300 px-3 py-1">Total Jastip (Rp)</th>
                            <th class="border border-gray-300 px-3 py-1">Jumlah Pemesan</th>
                            <th class="border border-gray-300 px-3 py-1">Nama Pemesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perBarang as $barang)
                            <tr>
                                <td class="border border-gray-300 px-3 py-1">{{ $barang['barang'] }}</td>
                                <td class="border border-gray-300 px-3 py-1 capitalize">{{ $barang['jenis'] }}</td>
                                <td class="border border-gray-300 px-3 py-1">{{ $barang['total_berat'] ?? '-' }}</td>
                                <td class="border border-gray-300 px-3 py-1">{{ $barang['total_jumlah'] ?? '-' }}</td>
                                <td class="border border-gray-300 px-3 py-1">
                                    {{ number_format($barang['total_harga'], 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-3 py-1">
                                    {{ number_format($barang['total_jastip'], 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-3 py-1">{{ $barang['jumlah_pemesan'] }}</td>
                                <td class="border border-gray-300 px-3 py-1">
                                    {{ $barang['daftar_pemesan']->join(', ') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

        </div>
    </div>



    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

</x-app-layout>
