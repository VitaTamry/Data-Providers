<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

class DataProviderW extends DataProviderBase
{
    public $name = 'DataProviderW';

    protected $transactions = [];
    protected $statusMap = [
        TransactionStatusEnum::paid->value => 'done',
        TransactionStatusEnum::pending->value => 'wait',
        TransactionStatusEnum::reject->value => 'nope'
    ];
    protected $filePath = 'app/providers/DataProviderW.json';
}
