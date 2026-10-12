<?php

namespace App\Policies;

use App\Models\NavigationMenu;
use App\Models\User;

class NavigationMenuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function view(User $user, NavigationMenu $menu): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function update(User $user, NavigationMenu $menu): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function delete(User $user, NavigationMenu $menu): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }
}
