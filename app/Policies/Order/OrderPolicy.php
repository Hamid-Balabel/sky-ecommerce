<?php

namespace App\Policies\Order;

use App\Enums\UserTypeEnum;
use App\Models\Product;
use App\Models\User;

class OrderPolicy
{

    public function adminOnly(User $user): bool
    {
        return $user->type === UserTypeEnum::Admin->value;
    }

}
