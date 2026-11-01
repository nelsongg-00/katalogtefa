<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Admin Jurusan hanya boleh mengubah projek milik jurusannya.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->ownsJurusan($project->jurusan_id);
    }

    /**
     * Admin Jurusan hanya boleh menghapus projek milik jurusannya.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->ownsJurusan($project->jurusan_id);
    }
}
