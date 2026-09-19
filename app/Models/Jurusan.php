<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_jurusan',
        'deskripsi_profil',
    ];

    /**
     * @return HasMany<Produk, $this>
     */
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class, 'jurusan_id');
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'jurusan_id');
    }
}
