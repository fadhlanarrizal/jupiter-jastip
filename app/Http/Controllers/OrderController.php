<?php

namespace App\Http\Controllers;

use App\Models\Order;

use Illuminate\Http\Request;
use App\Providers\GoogleSheetService;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{

    private function getRekapPerPemesan($orders)
    {
        return $orders->groupBy('nama_pemesan')->map(function ($items, $nama) {
            $totalBelanja = 0;
            $totalJastip = 0;

            $detail = $items->map(function ($item) use (&$totalBelanja, &$totalJastip) {
                $harga = $item->jenis == 'camilan'
                    ? $item->harga * $item->berat
                    : $item->harga * $item->jumlah;

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
                return $carry + (($item->jenis == 'camilan')
                    ? $item->harga * $item->berat
                    : $item->harga * $item->jumlah);
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
            'jenis' => 'required|in:camilan,non-camilan',
            'catatan' => 'nullable|string',
            'nama_pemesan' => 'required|string',
            'no_hp' => 'required|string',
        ]);

        $jenis = $request->jenis;
        $biaya = 0;
        $harga = null;
        $jumlah = null;
        $berat = null;

        if ($jenis === 'camilan') {
            $request->validate([
                'berat' => 'required|numeric|min:0.1',
                'harga' => 'required|numeric|min:1',
            ]);

            $berat = $request->berat;
            $harga = $request->harga;

            $totalHargaBarang = $harga * $berat;
            $biayaJastip = 30000 * $berat;
            $biaya = $totalHargaBarang + $biayaJastip;
        } else {
            $request->validate([
                'harga' => 'required|numeric|min:1',
                'jumlah' => 'required|integer|min:1',
            ]);

            $harga = $request->harga;
            $jumlah = $request->jumlah;
            $totalHarga = $harga * $jumlah;
            $persen = $harga > 100000 ? 0.2 : 0.3;
            $biaya = intval($totalHarga * $persen);
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
            'status' => $request->status, // default status saat store
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
            'jenis' => 'required|in:camilan,non-camilan',
            'catatan' => 'nullable|string',
            'nama_pemesan' => 'required|string',
            'no_hp' => 'required|string',
            'status' => 'required|in:pending,lunas,pengiriman,diterima',
        ]);

        $jenis = $request->jenis;
        $biaya = 0;
        $harga = null;
        $jumlah = null;
        $berat = null;

        if ($jenis === 'camilan') {
            $request->validate([
                'berat' => 'required|numeric|min:0.1',
                'harga' => 'required|numeric|min:1',
            ]);

            $berat = $request->berat;
            $harga = $request->harga;

            $totalHargaBarang = $harga * $berat;
            $biayaJastip = 30000 * $berat;
            $biaya = $totalHargaBarang + $biayaJastip;
        } else {
            $request->validate([
                'harga' => 'required|numeric|min:1',
                'jumlah' => 'required|integer|min:1',
            ]);

            $harga = $request->harga;
            $jumlah = $request->jumlah;
            $totalHarga = $harga * $jumlah;
            $persen = $harga > 100000 ? 0.2 : 0.3;
            $biaya = intval($totalHarga * $persen);
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
            'status' => $request->status, // disimpan saat update
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
