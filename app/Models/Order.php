<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_phone',
        'service_id',
        'worker_id',
        'project_id',
        'status',
        'total_biaya',
        'catatan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_biaya' => 'integer',
        ];
    }

    /**
     * Relasi ke layanan jasa.
     *
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Relasi ke worker penanggung jawab.
     *
     * @return BelongsTo<User, $this>
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /**
     * Relasi ke project pengerjaan.
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Accessor untuk mengambil progress logs dari project terkait.
     *
     * @return Collection<int, ProjectLog>
     */
    public function getProgressLogsAttribute(): Collection
    {
        if ($this->project) {
            return $this->project->projectLogs()->latest()->get();
        }

        return new Collection;
    }
}
