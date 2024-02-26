<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case paid = 'paid';
    case pending = 'pending';
    case reject = 'reject';
}
