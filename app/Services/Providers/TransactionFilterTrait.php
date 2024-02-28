<?php

namespace App\Services\Providers;

trait TransactionFilterTrait
{

    public function applyFilters($filters)
    {
        foreach ($filters as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->transactions =   $this->$filter($this->transactions, $value);
            }
        }
    }

    /**
     * Filter transactions by status
     * @param array $transactions
     * @param string $status
     * @return array
     */
    public function statusCode($transactions, $status): array
    {
        return array_filter($transactions, function ($transaction) use ($status) {
            return $transaction[$this->getProviderTransactionsKeys()['status']] ==
                $this->getProviderTransactionsStatusMap()[$status];
        });
    }

    /**
     * Filter transactions by currency
     * @param array $transactions
     * @param string $currency
     * @return array
     */
    public function currency($transactions, $currency): array
    {
        return array_filter($transactions, function ($transaction) use ($currency) {
            return $transaction[$this->getProviderTransactionsKeys()['currency']] == $currency;
        });
    }

    /**
     * Filter transactions by minimum amount
     * @param array $transactions
     * @param int $min
     * @return array
     */
    public function amountMin($transactions, $min = -INF): array
    {
        return array_filter($transactions, function ($transaction) use ($min) {
            return $transaction[$this->getProviderTransactionsKeys()['amount']] >= $min;
        });
    }
    /**
     * Filter transactions by maximum amount
     * @param array $transactions
     * @param int $max
     * @return array
     */
    public function amountMax($transactions, $max = INF): array
    {
        return array_filter($transactions, function ($transaction) use ($max) {
            return  $transaction[$this->getProviderTransactionsKeys()['amount']] <= $max;
        });
    }
}
