<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Order extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'service_id',
        'product_id',
        'department_id',
        'jumlah',
        'worker_id',
        'project_id',
        'status',
        'total_biaya',
        'total_harga',
        'metode_pembayaran',
        'metode_pengiriman',
        'lokasi_pengambilan',
        'catatan',
        'catatan_pelanggan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'total_biaya' => 'integer',
            'total_harga' => 'integer',
        ];
    }

    /**
     * Relasi ke pelanggan pemesan.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke produk fisik.
     *
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Alias for department_id for consistency across codebase.
     */
    public function getJurusanIdAttribute(): ?int
    {
        return $this->department_id;
    }

    /**
     * Relasi ke jurusan / department asal produk atau pesanan.
     *
     * @return BelongsTo<Jurusan, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'department_id');
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
     * Relasi ke log aktivitas / riwayat pesanan fisik.
     *
     * @return HasMany<OrderLog, $this>
     */
    public function orderLogs(): HasMany
    {
        return $this->hasMany(OrderLog::class, 'order_id')->latest();
    }

    /**
     * Mengecek apakah order ini adalah produk fisik.
     */
    public function isPhysicalProduct(): bool
    {
        return ! empty($this->product_id);
    }

    /**
     * Relasi ke log progres pengerjaan project dari worker.
     *
     * @return HasManyThrough<ProjectLog, Project, $this>
     */
    public function progressLogs(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProjectLog::class,
            Project::class,
            'id',          // Foreign key on Project table
            'project_id',  // Foreign key on ProjectLog table
            'project_id',  // Local key on Order table
            'id'           // Local key on Project table
        )->latest();
    }

    /**
     * Accessor untuk mengambil progress logs dari project terkait.
     *
     * @return Collection<int, ProjectLog>
     */
    public function getProgressLogsAttribute(): Collection
    {
        if ($this->relationLoaded('progressLogs')) {
            return $this->getRelation('progressLogs');
        }

        if ($this->project) {
            return $this->project->projectLogs()->with('worker')->latest()->get();
        }

        return new Collection;
    }
}
