<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role->name, ['owner', 'admin', 'staff']);
    }

    public function view(User $user, Category $category)
    {
        return in_array($user->role->name, ['owner', 'admin', 'staff']);
    }

    public function create(User $user)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }

    public function update(User $user, Category $category)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }

    public function delete(User $user, Category $category)
    {
        return in_array($user->role->name, ['owner', 'admin']);
    }
}
