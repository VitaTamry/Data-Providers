<?php

namespace App\Services;

use App\Services\providers\DataProvider;
use Illuminate\Http\Client\Request;

class TransactionsService
{

    private $providers = [
        'DataProviderX' => App\Services\DataProviderXService::class,
        'DataProviderY' => App\Services\DataProviderYService::class,
        'DataProviderZ' => App\Services\DataProviderZService::class
    ];
    public function getTransactions(Request $request)
    {
        $providerQ = $request->input('provider');
        $transactions = [];
        foreach ($this->providers as $key => $providerClass) {
            if (!$providerQ || $providerQ == $key) {
                $providerClass = $this->getTransactionsByProvider($request, new $providerClass());
                $transactions = array_merge($transactions, $providerClass);
            }
        }
        return $transactions;
    }

    /**
     * get all transactions from a specific provider
     * @param Request $request
     * @param DataProvider $provider
     * @return array
     */
    public function getTransactionsByProvider(Request $request, DataProvider $provider): array
    {
        return $provider->getTransactions($request);
    }
}
