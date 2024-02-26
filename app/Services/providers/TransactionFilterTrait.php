<?php

namespace App\Services\Providers;

trait TransactionFilterTrait
{
    public function applyFilters($request, $transactions): array
    {
        if ($request->has('statusCode') && $status = $request->input('statusCode')) {
            $transactions = $this->filterByStatus($transactions, $status);
        }
        if ($request->has('currency') && $currency = $request->input('currency')) {
            $transactions = $this->filterByCurrency($transactions, $currency);
        }
        if (($request->has('amountMin') && $min = $request->input('amountMin')) || ($request->has('amountMax') && $max = $request->input('amountMax'))) {
            $transactions = $this->filterByAmountRange($transactions, $min, $max);
        }


        return $transactions;
    }

    /**
     * Filter transactions by status
     * @param array $transactions
     * @param string $status
     * @return array
     */
    public function filterByStatus($transactions, $status): array
    {
        return array_filter($transactions, function ($transaction) use ($status) {
            return $transaction[$this->getProviderTransactionsKeys()['status']] == $this->getProviderTransactionsStatusMap()[$status];
        });
    }

    /**
     * Filter transactions by currency
     * @param array $transactions
     * @param string $currency
     * @return array
     */
    public function filterByCurrency($transactions, $currency): array
    {
        return array_filter($transactions, function ($transaction) use ($currency) {
            return $transaction[$this->getProviderTransactionsKeys()['currency']] == $currency;
        });
    }

    /**
     * Filter transactions by amount range
     * @param array $transactions
     * @param int $min
     * @param int $max
     * @return array
     */
    public function filterByAmountRange($transactions, $min = -INF, $max = INF): array
    {
        return array_filter($transactions, function ($transaction) use ($min, $max) {
            return $transaction[$this->getProviderTransactionsKeys()['amount']] >= $min && $transaction[$this->getProviderTransactionsKeys()['amount']] <= $max;
        });
    }


    /**
     * provider keys adapter
     * @return array
     */
    public function getProviderTransactionsKeys(): array
    {
        return [
            'amount' => $this->attributesMap['amount'],
            'currency' => $this->attributesMap['currency'],
            'date' => $this->attributesMap['date'],
            'id' => $this->attributesMap['id'],
            'phone' => $this->attributesMap['phone'],
            'status' => $this->attributesMap['status'],
        ];
    }

    /**
     * provider status map adapter
     * @return array
     */
    public function getProviderTransactionsStatusMap(): array
    {
        return $this->statusMap;
    }
}
