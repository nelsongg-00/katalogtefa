<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'file_hasil',
        'catatan_worker',
        'status_review',
        'catatan_revisi_admin',
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

    /**
     * Get the timeline logs for the project.
     *
     * @return HasMany<ProjectLog, $this>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ProjectLog::class, 'project_id')->orderBy('created_at', 'desc');
    }
}
