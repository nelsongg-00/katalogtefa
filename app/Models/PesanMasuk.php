<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanMasuk extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'jurusan_id',
        'nama_pengirim',
        'email',
        'subjek',
        'pesan',
        'is_read',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
