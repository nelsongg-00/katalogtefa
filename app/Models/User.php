<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jurusan_id',
        'is_active',
        'phone',
        'foto_profil',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Public URL of the profile photo, or null when the user has none.
     */
    public function getFotoProfilUrlAttribute(): ?string
    {
        return $this->foto_profil ? asset('storage/'.$this->foto_profil) : null;
    }

    /**
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    /**
     * Alias for jurusan_id for consistency across codebase.
     */
    public function getDepartmentIdAttribute(): ?int
    {
        return $this->jurusan_id;
    }

    /**
     * @return HasMany<Pesanan, $this>
     */
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'user_id');
    }

    /**
     * @return HasMany<Penugasan, $this>
     */
    public function penugasans(): HasMany
    {
        return $this->hasMany(Penugasan::class, 'worker_id');
    }

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'worker_id');
    }

    /**
     * ID jurusan yang boleh diakses user ini, termasuk jurusan ber-kode sama.
     *
     * @return list<int>
     */
    public function jurusanIds(): array
    {
        $ids = $this->jurusan_id ? [(int) $this->jurusan_id] : [];

        if ($this->jurusan && $this->jurusan->kode) {
            $ids = array_merge(
                $ids,
                Jurusan::where('kode', $this->jurusan->kode)->pluck('id')->map(fn ($id) => (int) $id)->all()
            );
        }

        return array_values(array_unique($ids));
    }

    /**
     * Super admin bebas; role lain hanya untuk jurusannya sendiri.
     */
    public function ownsJurusan(?int $jurusanId): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        return $jurusanId !== null && in_array((int) $jurusanId, $this->jurusanIds(), true);
    }

    /**
     * Pesanan legacy tidak punya kolom jurusan; pemiliknya ditentukan dari produknya.
     */
    public function ownsPesananJurusan(Pesanan $pesanan): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        return $pesanan->detailPesanans->contains(
            fn (DetailPesanan $detail) => $this->ownsJurusan($detail->produk?->jurusan_id ?? $detail->product?->jurusan_id)
        );
    }
}
