<?php

namespace App\Policies;

use App\Models\Lawyer;
use App\Models\User;

class LawyerPolicy
{
    /**
     * Admin bypasses all policy checks automatically.
     */
    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    /**
     * Any authenticated user can view the lawyers list (controller scopes by role).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Lawyers may only view their own profile page.
     */
    public function view(User $user, Lawyer $lawyer): bool
    {
        return $user->lawyer_id === $lawyer->id;
    }

    /**
     * Only admin may create lawyer accounts (admin passes via before()).
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * A lawyer may update their own profile; admin can update any.
     */
    public function update(User $user, Lawyer $lawyer): bool
    {
        return $user->lawyer_id === $lawyer->id;
    }

    /**
     * Only admin may delete lawyer records (admin passes via before()).
     */
    public function delete(User $user, Lawyer $lawyer): bool
    {
        return false;
    }
}
