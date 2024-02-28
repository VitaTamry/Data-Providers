<?php

namespace App\Services;

use App\Services\Providers\DataProvider;


class TransactionsService
{


    private $providers = [
        'DataProviderX' => Providers\DataProviderX::class,
        'DataProviderY' => Providers\DataProviderY::class,
        'DataProviderW' => Providers\DataProviderW::class
    ];

    // will be used to get all available providers for the api request validation
    public $availableProviders = [
        'DataProviderX',
        'DataProviderY',
        'DataProviderW'
    ];

    /**
     * get all transactions from all providers
     * @param array $filter
     * @return array
     */
    public function getTransactions(array $filter)
    {
        $providerQ = isset($filter['provider']) ? $filter['provider'] : null;
        $transactions = [];
        foreach ($this->providers as $key => $providerClass) {
            if (!$providerQ || $providerQ == $key) {
                if (!class_exists($providerClass)) {
                    continue;
                }
                $providerClass        = new $providerClass;
                $providerTransactions = $this->getTransactionsByProvider($filter, $providerClass);
                $transactions = array_merge($transactions, $providerTransactions);
            }
        }
        return $transactions;
    }

    /**
     * get all transactions from a specific provider
     * @param array $filter
     * @param DataProvider $provider
     * @return array
     */
    public function getTransactionsByProvider(array $filter, DataProvider $provider): array
    {
        return   $provider->getTransactions($filter);
    }
}
