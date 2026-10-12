<?php

namespace App\Policies;

use App\Models\Galeri;
use App\Models\User;

class GaleriPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function view(User $user, Galeri $galeri): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function update(User $user, Galeri $galeri): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function delete(User $user, Galeri $galeri): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }
}
