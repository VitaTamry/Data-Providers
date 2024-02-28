<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderX implements DataProvider
{
    use TransactionFilterTrait;
    public $name = 'DataProviderX';

    private $transactions = [];
    private $statusMap = [
        TransactionStatusEnum::paid->value => '1',
        TransactionStatusEnum::pending->value => '2',
        TransactionStatusEnum::reject->value => '3'
    ];

    private $attributesMap = [
        'amount' => 'transactionAmount',
        'currency' => 'Currency',
        'created_at' => 'transactionDate',
        'id' => 'transactionIdentification',
        'phone' => 'senderPhone',
        'status' => 'transactionStatus'
    ];

    private $filePath = 'app/providers/DataProviderX.json';
}
