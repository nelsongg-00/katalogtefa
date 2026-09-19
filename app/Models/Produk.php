<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'jurusan_id',
        'tipe',
        'nama_produk',
        'harga',
        'deskripsi',
        'foto_produk',
        'nomor_wa',
        'stok',
    ];

    /**
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    /**
     * @return HasMany<DetailPesanan, $this>
     */
    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'produk_id');
    }
}
