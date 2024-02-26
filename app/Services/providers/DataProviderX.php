<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;
use stdClass;

class DataProviderX implements DataProvider
{
    use TransactionFilterTrait;
    public $name = 'DataProviderX';
    private $transactions = [];
    private $statusMap = [
        TransactionStatusEnum::paid => '1',
        TransactionStatusEnum::pending => '2',
        TransactionStatusEnum::reject => '3'
    ];

    // attributes mapping
    private $attributesMap = [
        'amount' => 'transactionAmount',
        'currency' => 'Currency',
        'date' => 'transactionDate',
        'id' => 'transactionIdentification',
        'phone' => 'senderPhone',
        'status' => 'transactionStatus'
    ];

    // private $filePath = storage_path('app/providers/DataProviderX.csv');

    function __construct()
    {
        info('DataProviderX');
    }
    public function getTransactions(): DataProviderx
    {
        $transactions = [
            [
                'transactionAmount' => 200,
                'Currency' => 'USD',
                'senderPhone' => '00201234567890',
                'transactionStatus' => 1,
                'transactionDate' => '2021-03-29 09:36:11',
                'transactionIdentification' => 'd3d29d70-1d25-11e3-8591-034165a3a613'
            ],
            [
                'transactionAmount' => 100,
                'Currency' => 'EUR',
                'senderPhone' => '00201234567891',
                'transactionStatus' => 2,
                'transactionDate' => '2021-03-29 09:36:11',
                'transactionIdentification' => 'd3d29d70-1d25-11e3-8591-034165a3a614'
            ],
            [
                'transactionAmount' => 300,
                'Currency' => 'USD',
                'senderPhone' => '00201234567892',
                'transactionStatus' => 3,
                'transactionDate' => '2021-03-29 09:36:11',
                'transactionIdentification' => 'd3d29d70-1d25-11e3-8591-034165a3a615'
            ]
        ];
        $this->transactions = $transactions;
        return $this;
    }

    public function filterByAmountRange($transactions, $min = -INF, $max = INF): array
    {
        return array_filter($transactions, function ($transaction) use ($min, $max) {
            return $transaction['amount'] >= $min && $transaction['amount'] <= $max;
        });
    }
}
