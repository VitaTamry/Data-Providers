<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case paid = 'paid';
    case pending = 'pending';
    case reject = 'reject';

    public static function getValues(): array
    {
        return [
            self::paid->value,
            self::pending->value,
            self::reject->value
        ];
    }
}
