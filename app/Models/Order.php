<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama_pemesan',
        'no_hp',
        'nama_barang',
        'jenis',
        'berat',
        'harga',
        'jumlah',
        'catatan',
        'biaya',
        'status',
    ];
}
