<?php

namespace App\Policies\Product;

use App\Enums\UserTypeEnum;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{

    public function adminOnly(User $user): bool
    {
        return $user->type === UserTypeEnum::Admin->value;
    }
}
