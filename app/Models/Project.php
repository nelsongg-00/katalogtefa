<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'jurusan_id',
        'nama_projek',
        'deskripsi',
        'status',
        'progress',
        'worker_id',
        'tenggat_waktu',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'progress' => 'integer',
        'tenggat_waktu' => 'date',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /**
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
