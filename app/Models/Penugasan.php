<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penugasan extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'pesanan_id',
        'worker_id',
        'status_tugas',
        'tanggal_disposisi',
    ];

    /**
     * @return BelongsTo<Pesanan, $this>
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /**
     * @return HasMany<Progres, $this>
     */
    public function progres(): HasMany
    {
        return $this->hasMany(Progres::class, 'penugasan_id');
    }
}
