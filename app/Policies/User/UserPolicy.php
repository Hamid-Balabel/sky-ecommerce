<?php

namespace App\Policies\User;

use App\Enums\UserTypeEnum;
use App\Models\User;

class UserPolicy
{
    public function adminOnly(User $user): bool
    {
        return $user->type === UserTypeEnum::Admin->value;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->id == auth()->id() || $this->adminOnly($user);
    }

    public function update(User $user): bool
    {
        return $user->id == auth()->id() || $this->adminOnly($user);
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(User $user): bool
    {
        return $this->adminOnly($user);
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete(User $user): bool
    {
        return $this->adminOnly($user);
    }
}
