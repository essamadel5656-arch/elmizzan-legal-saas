<?php

namespace App\Policies;

use App\Models\LegalCase;
use App\Models\User;

class LegalCasePolicy
{
    /**
     * Admin bypasses all policy checks automatically.
     */
    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    /**
     * All authenticated roles can list cases (controller scopes by role).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * A lawyer can view only cases assigned to them.
     */
    public function view(User $user, LegalCase $case): bool
    {
        if ($user->role === 'lawyer') {
            return $case->lawyer_id === $user->lawyer_id
                || $case->lawyers()->where('lawyer_id', $user->lawyer_id)->exists();
        }
        return in_array($user->role, ['assistant']);
    }

    /**
     * Lawyers may create cases; admin passes via before().
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['lawyer']);
    }

    /**
     * A lawyer may update only cases where they are the lead.
     */
    public function update(User $user, LegalCase $case): bool
    {
        if ($user->role === 'lawyer') {
            return $case->lawyer_id === $user->lawyer_id;
        }
        return false;
    }

    /**
     * Only admin may delete cases (admin passes via before()).
     */
    public function delete(User $user, LegalCase $case): bool
    {
        return false;
    }
}
