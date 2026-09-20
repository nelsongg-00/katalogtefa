<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_jurusan',
        'slug',
        'kode',
        'deskripsi',
        'deskripsi_profil',
        'kepala_jurusan',
        'status_aktif',
        'lokasi_pengambilan',
    ];

    /**
     * Dapatkan lokasi pengambilan dengan fallback cerdas berbasis kode jurusan.
     */
    public function getLokasiPengambilanAttribute(?string $value): string
    {
        if (! empty($value)) {
            return $value;
        }

        $kode = strtoupper($this->kode ?? '');
        $nama = strtolower($this->nama_jurusan ?? '');

        return match (true) {
            $kode === 'RPL' || str_contains($nama, 'perangkat lunak') => 'Lab Komputer & Rekayasa Perangkat Lunak',
            $kode === 'TKJ' || str_contains($nama, 'komputer jaringan') => 'Lab Jaringan & Server TKJ',
            $kode === 'DKV' || str_contains($nama, 'komunikasi visual') => 'Lab Desain Grafis DKV',
            $kode === 'PSPT' || $kode === 'PSTV' || str_contains($nama, 'siaran') => 'Studio Siaran & Editing PSTV',
            $kode === 'ANI' || str_contains($nama, 'animasi') => 'Lab Animasi 2D/3D',
            $kode === 'GIM' || str_contains($nama, 'gim') || str_contains($nama, 'game') => 'Game Dev & VR Lab',
            default => 'Lab Teaching Factory SMKN 4 Tanjungpinang',
        };
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Produk, $this>
     */
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class, 'jurusan_id');
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'jurusan_id');
    }

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'jurusan_id');
    }

    /**
     * @return HasMany<PesanMasuk, $this>
     */
    public function pesanMasuks(): HasMany
    {
        return $this->hasMany(PesanMasuk::class, 'jurusan_id');
    }
}
