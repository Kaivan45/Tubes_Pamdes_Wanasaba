<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_meteran
 * @property int $totalbayar
 * @property string $status
 * @property string|null $tanggalbayar
 */
class Transaksi extends Model
{
    /** @use HasFactory<Factory<self>> */
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'id_meteran',
        'status',
        'tanggalbayar',
        'totalbayar',
    ];

    /**
     * Relasi ke model Data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Data, \App\Models\Transaksi>
     */
    public function data(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Data, \App\Models\Transaksi> $relation */
        $relation = $this->belongsTo(Data::class, 'id_meteran', 'id');

        return $relation;
    }

}
