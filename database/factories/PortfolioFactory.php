<?php

namespace Database\Factories;

use App\Models\Jurusan;
use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Portfolio>
 */
class PortfolioFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Tidak ada JurusanFactory di repo ini; pakai jurusan pertama atau buat satu.
            'jurusan_id' => fn () => Jurusan::query()->value('id')
                ?? Jurusan::query()->create(['nama_jurusan' => 'Rekayasa Perangkat Lunak', 'kode' => 'RPL'])->id,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'team_name' => 'Tim Siswa '.fake()->word().' SMKN 4',
        ];
    }
}
