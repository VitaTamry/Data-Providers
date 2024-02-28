<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderY extends DataProviderBase implements DataProvider
{
    use TransactionFilterTrait;
    public $name = 'DataProviderY';

    protected $transactions = [];
    protected $statusMap = [
        TransactionStatusEnum::paid->value => '100',
        TransactionStatusEnum::pending->value => '200',
        TransactionStatusEnum::reject->value => '300'
    ];

    protected $attributesMap = [
        'amount' => 'amount',
        'currency' => 'currency',
        'created_at' => 'created_at',
        'id' => 'id',
        'phone' => 'phone',
        'status' => 'status'
    ];
    protected $filePath = 'app/providers/DataProviderY.json';
}
