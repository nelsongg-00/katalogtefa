<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'status_pesanan',
        'total_harga',
        'is_service_via_wa',
        'tanggal_pesan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pesan' => 'date',
            'total_harga' => 'integer',
            'is_service_via_wa' => 'boolean',
        ];
    }

    public function getJurusanAttribute(): ?Jurusan
    {
        return $this->detailPesanans->first()?->produk?->jurusan;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<DetailPesanan, $this>
     */
    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    /**
     * @return HasMany<Penugasan, $this>
     */
    public function penugasans(): HasMany
    {
        return $this->hasMany(Penugasan::class, 'pesanan_id');
    }

    /**
     * @return HasMany<Pembayaran, $this>
     */
    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }
}
