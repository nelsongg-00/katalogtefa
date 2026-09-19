<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progres extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'penugasan_id',
        'keterangan_progres',
        'file_hasil_akhir',
        'tanggal_update',
    ];

    /**
     * @return BelongsTo<Penugasan, $this>
     */
    public function penugasan(): BelongsTo
    {
        return $this->belongsTo(Penugasan::class, 'penugasan_id');
    }
}
