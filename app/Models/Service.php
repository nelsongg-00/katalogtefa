<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'department_id',
        'nama_layanan',
        'slug',
        'deskripsi',
        'estimasi_harga',
        'foto',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'estimasi_harga' => 'integer',
        ];
    }

    /**
     * Relasi ke jurusan / departemen.
     *
     * @return BelongsTo<Jurusan, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'department_id');
    }

    /**
     * Alias jurusan untuk kemudahan pemanggilan.
     *
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'department_id');
    }

    /**
     * Relasi ke pesanan tracking.
     *
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'service_id');
    }
}
