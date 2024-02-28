<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderY implements DataProvider
{
    use TransactionFilterTrait;
    public $name = 'DataProviderY';

    private $transactions = [];
    private $statusMap = [
        TransactionStatusEnum::paid->value => '100',
        TransactionStatusEnum::pending->value => '200',
        TransactionStatusEnum::reject->value => '300'
    ];

    private $attributesMap = [
        'amount' => 'amount',
        'currency' => 'currency',
        'created_at' => 'created_at',
        'id' => 'id',
        'phone' => 'phone',
        'status' => 'status'
    ];
    private $filePath = 'app/providers/DataProviderY.json';
}
