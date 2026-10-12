<?php

namespace App\Policies;

use App\Models\Halaman;
use App\Models\User;

class HalamanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function view(User $user, Halaman $halaman): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function update(User $user, Halaman $halaman): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function delete(User $user, Halaman $halaman): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }
}
