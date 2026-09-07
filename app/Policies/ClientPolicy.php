<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Admin bypasses all policy checks automatically.
     */
    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    /**
     * Lawyers and assistants may view the client list (controller scopes by role).
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'lawyer', 'assistant']);
    }

    /**
     * Lawyers and assistants may view individual client records.
     */
    public function view(User $user, Client $client): bool
    {
        return in_array($user->role, ['admin', 'lawyer', 'assistant']);
    }

    /**
     * Lawyers and assistants may create clients; admin passes via before().
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['lawyer', 'assistant']);
    }

    /**
     * Lawyers and assistants may update clients.
     */
    public function update(User $user, Client $client): bool
    {
        return in_array($user->role, ['lawyer', 'assistant']);
    }

    /**
     * Only admin may delete clients (admin passes via before()).
     */
    public function delete(User $user, Client $client): bool
    {
        return false;
    }
}
