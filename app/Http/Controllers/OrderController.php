<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

    private function hitungBiaya($jenis, $harga, $jumlah = 0, $berat = 0)
    {
        if ($jenis === 'camilan-kiloan') {
            return $harga * $berat + 30000 * $berat;
        } elseif ($jenis === 'camilan-satuan') {
            $totalBerat = $berat * $jumlah;
            return 30000 * $totalBerat ;
        } else { // non-camilan
            $total = $harga * $jumlah;
            $persen = $harga > 100000 ? 0.2 : 0.3;
            return intval($total * $persen);
        }
    }


    private function getRekapPerPemesan($orders)
    {
        return $orders->groupBy('nama_pemesan')->map(function ($items, $nama) {
            $totalBelanja = 0;
            $totalJastip = 0;

            $detail = $items->map(function ($item) use (&$totalBelanja, &$totalJastip) {
                $harga = match ($item->jenis) {
                    'camilan-kiloan' => $item->harga * $item->berat,
                    'camilan-satuan', 'non-camilan' => $item->harga * $item->jumlah,
                    default => 0,
                };

                $totalBelanja += $harga;
                $totalJastip += $item->biaya;

                return [
                    'nama_barang' => $item->nama_barang,
                    'jenis' => $item->jenis,
                    'berat' => $item->berat,
                    'harga' => $item->harga,
                    'jumlah' => $item->jumlah,
                    'total' => $harga,
                    'jastip' => $item->biaya,
                    'catatan' => $item->catatan,
                ];
            });

            return [
                'nama' => $nama,
                'no_hp' => $items->first()->no_hp,
                'detail' => $detail,
                'total_belanja' => $totalBelanja,
                'total_jastip' => $totalJastip,
                'total_semua' => $totalBelanja + $totalJastip,
            ];
        });
    }

    private function getRekapPerBarang($orders)
    {
        return $orders->groupBy('nama_barang')->map(function ($items, $barang) {
            $totalBerat = $items->sum('berat');
            $totalJumlah = $items->sum('jumlah');
            $totalHarga = $items->reduce(function ($carry, $item) {
                return $carry + match ($item->jenis) {
                    'camilan-kiloan' => $item->harga * $item->berat,
                    'camilan-satuan', 'non-camilan' => $item->harga * $item->jumlah,
                    default => 0,
                };
            }, 0);
            $totalJastip = $items->sum('biaya');
            $pemesan = $items->pluck('nama_pemesan')->unique();

            return [
                'barang' => $barang,
                'jenis' => $items->first()->jenis,
                'total_berat' => $totalBerat,
                'total_jumlah' => $totalJumlah,
                'total_harga' => $totalHarga,
                'total_jastip' => $totalJastip,
                'jumlah_pemesan' => $pemesan->count(),
                'daftar_pemesan' => $pemesan,
            ];
        });
    }

    private function getFilteredOrders($filter)
    {
        $query = Order::query();

        switch ($filter) {
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
                break;
            case 'quarter':
                $query->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()]);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        return $query->get();
    }

    public function dashboard(Request $request)
    {
        $filter = $request->input('filter', 'month');

        $orders = $this->getFilteredOrders($filter);
        $perPemesan = $this->getRekapPerPemesan($orders);
        $perBarang = $this->getRekapPerBarang($orders);

        return view('user.dashboard', compact('perPemesan', 'perBarang', 'filter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'jenis' => 'required|in:camilan-kiloan,camilan-satuan,non-camilan',
            'catatan' => 'nullable|string',
            'nama_pemesan' => 'required|string',
            'no_hp' => 'required|string',
        ]);

        $jenis = $request->jenis;
        $biaya = 0;
        $harga = $request->harga;
        $jumlah = $jenis !== 'camilan-kiloan' ? $request->jumlah : null;
        $berat = $jenis !== 'non-camilan' ? $request->berat : null;



        if ($jenis === 'camilan-kiloan') {
            $request->validate([
                'berat' => 'required|numeric|min:0.1',
                'harga' => 'required|numeric|min:1',
            ]);

            $biaya = $this->hitungBiaya($jenis, $harga, $jumlah, $berat);
        } elseif ($jenis === 'camilan-satuan') {
            $request->validate([
                'berat' => 'required|numeric|min:0.1', // berat per satuan
                'jumlah' => 'required|integer|min:1',
                'harga' => 'required|numeric|min:1',
            ]);
            $biaya = $this->hitungBiaya($jenis, $harga, $jumlah, $berat);
        } else { // non-camilan
            $request->validate([
                'harga' => 'required|numeric|min:1',
                'jumlah' => 'required|integer|min:1',
            ]);
            $biaya = $this->hitungBiaya($jenis, $harga, $jumlah, $berat);
        }

        Order::create([
            'nama_barang' => $request->nama_barang,
            'jenis' => $jenis,
            'berat' => $berat,
            'harga' => $harga,
            'jumlah' => $jumlah,
            'catatan' => $request->catatan,
            'biaya' => $biaya,
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'status' => $request->status ?? 'pending', // default status jika tidak ada input
        ]);

        return redirect()->route('pesan.riwayat')->with('success', 'Pesanan berhasil dikirim!');
    }

    public function edit(Order $order)
    {
        return view('user.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'jenis' => 'required|in:camilan-kiloan,camilan-satuan,non-camilan',
            'catatan' => 'nullable|string',
            'nama_pemesan' => 'required|string',
            'no_hp' => 'required|string',
            'status' => 'required|in:pending,lunas,pengiriman,diterima',
        ]);

        $jenis = $request->jenis;
        $biaya = 0;
        $harga = $request->harga;
        $jumlah = $jenis !== 'camilan-kiloan' ? $request->jumlah : null;
        $berat = $jenis !== 'non-camilan' ? $request->berat : null;



        if ($jenis === 'camilan-kiloan') {
            $request->validate([
                'berat' => 'required|numeric|min:0.1',
                'harga' => 'required|numeric|min:1',
            ]);

            $biaya = $this->hitungBiaya($jenis, $harga, $jumlah, $berat);
        } elseif ($jenis === 'camilan-satuan') {
            $request->validate([
                'berat' => 'required|numeric|min:0.1',
                'jumlah' => 'required|integer|min:1',
                'harga' => 'required|numeric|min:1',
            ]);
            $biaya = $this->hitungBiaya($jenis, $harga, $jumlah, $berat);
        } else { // non-camilan
            $request->validate([
                'harga' => 'required|numeric|min:1',
                'jumlah' => 'required|integer|min:1',
            ]);
            $biaya = $this->hitungBiaya($jenis, $harga, $jumlah, $berat);
        }

        $order->update([
            'nama_barang' => $request->nama_barang,
            'jenis' => $jenis,
            'berat' => $berat,
            'harga' => $harga,
            'jumlah' => $jumlah,
            'catatan' => $request->catatan,
            'biaya' => $biaya,
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ]);

        return redirect()->route('pesan.riwayat')->with('success', 'Pesanan berhasil diperbarui!');
    }


    public function index()
    {
        $orders = Order::latest()->get();
        return view('user.riwayat', compact('orders'));
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->input('filter', 'month');

        $orders = $this->getFilteredOrders($filter);
        $perPemesan = $this->getRekapPerPemesan($orders);
        $perBarang = $this->getRekapPerBarang($orders);

        $pdf = Pdf::loadView('user.pdf', compact('perPemesan', 'perBarang', 'filter'));
        return $pdf->download('rekap-orderan-' . now()->format('Ymd_His') . '.pdf');
    }
}
