<?php

namespace App\Enums;


enum UserTypeEnum: string
{
    // DEFINING CONSTANTS
    case Customer = 'customer';
    case Admin = 'admin';
    /**
     * @return string
     */
    public static function defaultStatus(): string
    {
        return self::Customer->value;
    }
}
