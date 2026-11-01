<?php

namespace App\Policies;

use App\Models\Penugasan;
use App\Models\User;

class PenugasanPolicy
{
    /**
     * Worker hanya boleh mengubah progres tugasnya sendiri; Admin Jurusan hanya untuk jurusannya.
     */
    public function update(User $user, Penugasan $penugasan): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        if ($penugasan->worker_id === $user->id) {
            return true;
        }

        return $penugasan->pesanan !== null && $user->ownsPesananJurusan($penugasan->pesanan);
    }
}
