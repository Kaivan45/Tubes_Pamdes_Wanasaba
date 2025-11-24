<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Data extends Model
{
    use HasFactory;

    protected $table = 'data';
    protected $fillable = ['user_id', 'meteran', 'harga', 'tanggal', 'status', 'slug'];
    protected $with = ['user'];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Boot method untuk model
    protected static function booted()
    {
        static::creating(function ($data) {
            // Jika belum ada slug, buat otomatis
            if (empty($data->slug)) {
                $data->slug = $data->user_id . uniqid();
            }
        });
    }
    // Fungsi scope untuk filter pencarian
    public function scopeFilter(Builder $query): Builder
    {
        if ($search = request('search')) {
            $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('alamat', 'like', "%$search%")
              ->orWhere('noHp', 'like', "%$search%");
            });
        }

        return $query;
    }

    // Fungsi scope untuk mendapatkan data terakhir per user
    public function scopeLastPerUser($query)
    {
        return $query->select('data.*')
            ->join(DB::raw('(SELECT MAX(id) as id FROM data GROUP BY user_id) as latest'), 'data.id', '=', 'latest.id');
    }

    // Fungsi scope untuk mengurutkan data dengan status 'Belum Lunas' di atas
    public function scopeBelumLunasFirst($query)
    {
        return $query->orderByRaw("CASE WHEN status = 'Belum Lunas' THEN 1 ELSE 2 END")
                    ->latest();
    }

    // Override getRouteKeyName untuk menggunakan slug sebagai route key
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

