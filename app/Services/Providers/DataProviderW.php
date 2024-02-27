<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderW implements DataProvider
{
    use TransactionFilterTrait;
    public $name = 'DataProviderW';

    private $transactions = [];
    private $statusMap = [
        TransactionStatusEnum::paid->value => 'done',
        TransactionStatusEnum::pending->value => 'wait',
        TransactionStatusEnum::reject->value => 'nope'
    ];

    private $attributesMap = [
        'amount' => 'amount',
        'currency' => 'currency',
        'created_at' => 'created_at',
        'id' => 'id',
        'phone' => 'phone',
        'status' => 'status'
    ];
    private $filePath = 'app/providers/DataProviderW.json';

    function __construct()
    {
        info('DataProviderW');
    }
}
