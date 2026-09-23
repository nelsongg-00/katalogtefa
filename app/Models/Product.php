<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'jurusan_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
    ];

    /**
     * Alias for jurusan_id for consistency across codebase.
     */
    public function getDepartmentIdAttribute(): ?int
    {
        return $this->jurusan_id;
    }

    /**
     * Get the department that owns the product.
     *
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
