<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<Factory<self>> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'alamat',
        'noHp',
        'role',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke model Data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Data, \App\Models\User>
     */
    public function data(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Data, \App\Models\User> $relation */
        $relation = $this->hasMany(Data::class, 'user_id');

        return $relation;
    }


    /**
     * Menghitung total user dengan role 'pelanggan'
     *
     * @return int
     */
    public static function totalRegularUsers(): int
    {
        return self::where('role', 'pelanggan')->count();
    }

    /**
     * Gunakan username sebagai route key
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }
}
