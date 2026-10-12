<?php

namespace App\Policies;

use App\Models\DokumenInformasi;
use App\Models\User;

class DokumenInformasiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_content') || $user->can('view_ppid') || $user->hasRole('Super Admin');
    }

    public function view(User $user, DokumenInformasi $doc): bool
    {
        return $user->can('manage_content') || $user->can('view_ppid') || $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function update(User $user, DokumenInformasi $doc): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }

    public function delete(User $user, DokumenInformasi $doc): bool
    {
        return $user->can('manage_content') || $user->hasRole('Super Admin');
    }
}
