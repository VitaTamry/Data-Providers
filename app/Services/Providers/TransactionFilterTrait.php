<?php

namespace App\Services\Providers;

trait TransactionFilterTrait
{

    public function applyFilters($filters, $transactions): array
    {
        foreach ($filters as $filter => $value) {
            if (method_exists($this, $filter) && count($transactions) > 0) {
                $transactions = $this->$filter($transactions, $value);
            }
        }
        return $transactions;
    }


    /**
     * Filter transactions by status
     * @param array $transactions
     * @param string $status
     * @return array
     */
    public function statusCode($transactions, $status)
    {
        return  array_filter($transactions, function ($transaction) use ($status) {
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
    public function currency($transactions, $currency)
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
    public function amountMin($transactions, $min = -INF)
    {
        return array_filter($transactions, function ($transaction) use ($min) {
            return ((float)$transaction[$this->getProviderTransactionsKeys()['amount']] >= (float)$min);
        });
    }

    /**
     * Filter transactions by maximum amount
     * @param array $transactions
     * @param int $max
     * @return array
     */
    public function amountMax($transactions, $max): array
    {
        return array_filter($transactions, function ($transaction) use ($max) {
            return ((float)$transaction[$this->getProviderTransactionsKeys()['amount']] <= (float) $max);
        });
    }
}
