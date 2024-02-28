<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderW extends DataProviderBase implements DataProvider
{
    use TransactionFilterTrait;
    public $name = 'DataProviderW';

    protected $transactions = [];
    protected $statusMap = [
        TransactionStatusEnum::paid->value => 'done',
        TransactionStatusEnum::pending->value => 'wait',
        TransactionStatusEnum::reject->value => 'nope'
    ];

    protected $attributesMap = [
        'amount' => 'amount',
        'currency' => 'currency',
        'created_at' => 'created_at',
        'id' => 'id',
        'phone' => 'phone',
        'status' => 'status'
    ];
    protected $filePath = 'app/providers/DataProviderW.json';
}
