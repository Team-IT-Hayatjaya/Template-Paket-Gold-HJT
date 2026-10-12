<?php

namespace App\Policies;

use App\Models\Banner;
use App\Models\User;

class BannerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function view(User $user, Banner $banner): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function update(User $user, Banner $banner): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function delete(User $user, Banner $banner): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }
}
