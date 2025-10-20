<?php

namespace App\Models;

use App\Models\Data;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    // Nama tabel (optional kalau sudah sesuai konvensi)
    protected $table = 'transaksis';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'id_meteran',
        'status',
        'tanggalbayar',
        'totalbayar',
    ];

    public function meteran()
    {
        return $this->belongsTo(Data::class, 'id_meteran');
    }
}


