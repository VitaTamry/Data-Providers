<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderY extends DataProviderBase
{
    public $name = 'DataProviderY';

    protected $transactions = [];
    protected $statusMap = [
        TransactionStatusEnum::paid->value => '100',
        TransactionStatusEnum::pending->value => '200',
        TransactionStatusEnum::reject->value => '300'
    ];
    protected $filePath = 'app/providers/DataProviderY.json';
}
