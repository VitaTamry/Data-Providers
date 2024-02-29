<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderX extends DataProviderBase
{
    public $name = 'DataProviderX';

    protected $transactions = [];
    protected $statusMap = [
        TransactionStatusEnum::paid->value => '1',
        TransactionStatusEnum::pending->value => '2',
        TransactionStatusEnum::reject->value => '3'
    ];

    protected $attributesMap = [
        'amount' => 'transactionAmount',
        'currency' => 'Currency',
        'created_at' => 'transactionDate',
        'id' => 'transactionIdentification',
        'phone' => 'senderPhone',
        'status' => 'transactionStatus'
    ];

    protected $filePath = 'app/providers/DataProviderX.json';
}
