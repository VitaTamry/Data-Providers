<?php

namespace App\Services\providers;

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
            return $transaction['status'] == $status;
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
            return $transaction['currency'] == $currency;
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
            return $transaction['amount'] >= $min && $transaction['amount'] <= $max;
        });
    }

    /**
     * Format transactions keys
     * @param array $transactions
     * @return array
     */
    public function formatTransactionsKeys($transactions): array
    {
        if (!$this->attributesMap) return $transactions;

        return array_map(function ($transaction) {
            return [
                'amount' => $transaction[$this->attributesMap['amount']],
                'currency' => $transaction[$this->attributesMap['currency']],
                'date' => $transaction[$this->attributesMap['date']],
                'id' => $transaction[$this->attributesMap['id']],
                'phone' => $transaction[$this->attributesMap['phone']],
                'status' => $transaction[$this->attributesMap['status']],
            ];
        }, $transactions);
    }
}
