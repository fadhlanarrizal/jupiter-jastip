<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Orderan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background: #eee; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body>
    <h1>Rekap Orderan ({{ ucfirst($filter) }})</h1>

    <h2>Per Pemesan</h2>
    @foreach ($perPemesan as $pemesan)
        <strong>{{ $pemesan['nama'] }}</strong> - {{ $pemesan['no_hp'] }}<br>
        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Jenis</th>
                    <th>Berat</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Jastip</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pemesan['detail'] as $d)
                    <tr>
                        <td>{{ $d['nama_barang'] }}</td>
                        <td>{{ $d['jenis'] }}</td>
                        <td>{{ $d['berat'] ?? '-' }}</td>
                        <td>{{ number_format($d['harga'], 0, ',', '.') }}</td>
                        <td>{{ $d['jumlah'] ?? '-' }}</td>
                        <td>{{ number_format($d['total'], 0, ',', '.') }}</td>
                        <td>{{ number_format($d['jastip'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            Total Belanja: Rp {{ number_format($pemesan['total_belanja'], 0, ',', '.') }}<br>
            Total Jastip: Rp {{ number_format($pemesan['total_jastip'], 0, ',', '.') }}<br>
            <strong>Total Keseluruhan: Rp {{ number_format($pemesan['total_semua'], 0, ',', '.') }}</strong>
        </p>
    @endforeach

    <h2>Per Barang</h2>
    <table>
        <thead>
            <tr>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Total Berat</th>
                <th>Total Jumlah</th>
                <th>Total Harga</th>
                <th>Total Jastip</th>
                <th>Jumlah Pemesan</th>
                <th>Nama Pemesan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perBarang as $b)
                <tr>
                    <td>{{ $b['barang'] }}</td>
                    <td>{{ $b['jenis'] }}</td>
                    <td>{{ $b['total_berat'] ?? '-' }}</td>
                    <td>{{ $b['total_jumlah'] ?? '-' }}</td>
                    <td>{{ number_format($b['total_harga'], 0, ',', '.') }}</td>
                    <td>{{ number_format($b['total_jastip'], 0, ',', '.') }}</td>
                    <td>{{ $b['jumlah_pemesan'] }}</td>
                    <td>{{ $b['daftar_pemesan']->join(', ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
