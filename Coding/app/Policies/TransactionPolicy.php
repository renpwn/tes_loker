<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role->name, ['owner', 'admin', 'staff']);
    }

    public function view(User $user, Transaction $transaction)
    {
        return in_array($user->role->name, ['owner', 'admin', 'staff']);
    }

    public function create(User $user)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }

    public function update(User $user, Transaction $transaction)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }

    public function delete(User $user, Transaction $transaction)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }
}
