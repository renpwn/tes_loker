<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role->name, ['owner', 'admin', 'staff']);
    }

    public function view(User $user, Product $product)
    {
        return in_array($user->role->name, ['owner', 'admin', 'staff']);
    }

    public function create(User $user)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }

    public function update(User $user, Product $product)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }

    public function delete(User $user, Product $product)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }
}
