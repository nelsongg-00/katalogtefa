<?php

namespace App\Policies;

use App\Models\Pesanan;
use App\Models\User;

class PesananPolicy
{
    /**
     * Validasi pesanan hanya untuk jurusan pemilik produk di dalamnya.
     */
    public function validate(User $user, Pesanan $pesanan): bool
    {
        return $user->ownsPesananJurusan($pesanan);
    }

    /**
     * Penugasan pesanan hanya untuk jurusan pemilik produk di dalamnya.
     */
    public function assign(User $user, Pesanan $pesanan): bool
    {
        return $user->ownsPesananJurusan($pesanan);
    }
}
