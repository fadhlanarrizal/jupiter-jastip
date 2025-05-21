@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
  <div class="text-center space-y-4">
    <h1 class="text-3xl font-bold text-blue-700">Selamat Datang di Jupiter Jastip</h1>
    <p>Kami membantu kamu titip camilan dari Jogja ke Sorong via kapal.</p>

    <div class="bg-white shadow p-6 rounded-lg">
      <h2 class="text-xl font-semibold mb-2">Tarif Jasa Titip</h2>
      <ul class="list-disc pl-5 text-left">
        <li>Camilan: Rp30.000 per kg</li>
        <li>Barang lain:
          <ul class="list-disc ml-5">
            <li>Harga > Rp100.000 → jastip 20%</li>
            <li>Harga ≤ Rp100.000 → jastip 30%</li>
          </ul>
        </li>
      </ul>
    </div>

    <a href="/pesan" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Buat Pesanan</a>
  </div>
@endsection
