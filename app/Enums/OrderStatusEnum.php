<?php

namespace App\Enums;


enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case FAIL = 'fail';
    case PROGRESS = 'progress';
    case SUCCESSFUL = 'successful';

    /**
     * @return string
     */
    public static function defaultStatus(): string
    {
        return self::PENDING->value;
    }

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return [
            self::PENDING->value => __('pending'),
            self::REJECTED->value => __('rejected'),
            self::FAIL->value => __('fail'),
            self::PROGRESS->value => __('progress'),
            self::SUCCESSFUL->value => __('successful'),
        ];
    }
}
