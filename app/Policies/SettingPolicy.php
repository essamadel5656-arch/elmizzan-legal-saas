<?php

namespace App\Policies;

use App\Models\User;

class SettingPolicy
{
    /**
     * Only the admin (firm owner) can view or modify system settings.
     */
    public function manage(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Alias for manage — used by authorize('view', SettingPolicy).
     */
    public function view(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Alias for manage — used by authorize('update', SettingPolicy).
     */
    public function update(User $user): bool
    {
        return $user->role === 'admin';
    }
}
