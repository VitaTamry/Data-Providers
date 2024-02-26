<?php

namespace App\Services;

use App\Services\Providers\DataProvider;
use App\Services\Providers\DataProviderX;
// use App\Services\providers\DataProviderX;
// use App\Services\providers\DataProviderX;
use Illuminate\Http\Request;

class TransactionsService
{

    private $providers = [
        'DataProviderX' => 'App\Services\providers\DataProviderX',
        // 'DataProviderY' => App\Services\DataProviderYService::class,
        // 'DataProviderZ' => App\Services\DataProviderZService::class
    ];
    public function getTransactions(Request $request)
    {
        $providerQ = $request->input('provider');
        $transactions = [];
        foreach ($this->providers as $key => $providerClass) {
            if (!$providerQ || $providerQ == $key) {
                info($providerClass);
                if (!class_exists($providerClass)) {
                    info('Provider not found');
                    continue;
                }
                $providerClass        = new DataProviderX;

                $providerTransactions = $this->getTransactionsByProvider($request, $providerClass);
                $transactions = array_merge($transactions, $providerTransactions);
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
        if ($request->has('statusCode') || $request->has('currency') || $request->has('amountMin') || $request->has('amountMax')) {
            $transactions = $provider->getTransactions();
            return $provider->applyFilters($request, $transactions);
        }
        return $provider->getTransactions();
    }
}
