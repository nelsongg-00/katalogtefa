<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'jurusan_id',
        'title',
        'description',
        'team_name',
        'mentor_name',
    ];

    /**
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    /**
     * @return HasMany<PortfolioImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('order')->orderBy('id');
    }
}
