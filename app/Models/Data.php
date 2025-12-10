<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property int $user_id
 * @property string $meteran
 * @property string $harga
 * @property string $tanggal
 * @property string $status
 * @property string $slug
 *
 * @mixin Builder<Data>
 */
class Data extends Model
{
    /** @use HasFactory<Factory<self>> */
    use HasFactory;

    protected $table = 'data';

    protected $fillable = [
        'user_id',
        'meteran',
        'harga',
        'tanggal',
        'status',
        'slug',
    ];

    protected $with = ['user'];

    /**
     * Relasi ke User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\Data>
     */
    public function user(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\Data> $relation */
        $relation = $this->belongsTo(User::class, 'user_id', 'id');

        return $relation;
    }

    /**
     * Membuat slug otomatis
     */
    protected static function booted(): void
    {
        static::creating(function (Data $data): void {
            if (empty($data->slug)) {
                $data->slug = $data->user_id . uniqid();
            }
        });
    }

    /**
     * Scope filter pencarian.
     *
     * @param Builder<Data> $query
     * @return Builder<Data>
     */
    public function scopeFilter(Builder $query): Builder
    {
        $search = request('search');

        if (is_string($search) && strlen($search) > 0) {
            $query->whereHas('user', function (Builder $q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                    ->orWhere('noHp', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Scope: data terakhir per user
     *
     * @param Builder<Data> $query
     * @return Builder<Data>
     */
    public function scopeLastPerUser(Builder $query): Builder
    {
        return $query->select('data.*')
            ->join(
                DB::raw('(SELECT MAX(id) as id FROM data GROUP BY user_id) as latest'),
                'data.id',
                '=',
                'latest.id'
            );
    }

    /**
     * Scope: Belum Lunas diurutkan pertama
     *
     * @param Builder<Data> $query
     * @return Builder<Data>
     */
    public function scopeBelumLunasFirst(Builder $query): Builder
    {
        return $query
            ->orderByRaw("CASE WHEN status = 'Belum Lunas' THEN 1 ELSE 2 END")
            ->latest();
    }

    /**
     * Gunakan slug sebagai route model binding.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
