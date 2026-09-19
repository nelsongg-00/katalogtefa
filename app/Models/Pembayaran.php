<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'pesanan_id',
        'bukti_bayar',
        'status_bayar',
        'tanggal_upload',
    ];

    /**
     * @return BelongsTo<Pesanan, $this>
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
