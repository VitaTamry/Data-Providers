<?php

namespace App\Services\Providers;

trait TransactionFilterTrait
{

    public function getTransactions(): array
    {
        try {
            $transactions = json_decode(file_get_contents(storage_path($this->filePath)), true);
        } catch (\Exception $e) {
            info($e->getMessage());
            $transactions = [];
        }
        return $transactions;
    }


    public function applyFilters($filters, $transactions): array
    {
        if (isset($filters['statusCode'])) {
            $transactions = $this->filterByStatus($transactions, $filters['statusCode']);
        }
        if (isset($filters['currency'])) {
            $transactions = $this->filterByCurrency($transactions, $filters['currency']);
        }
        if (isset($filters['amountMin']) || isset($filters['amountMax'])) {
            $min = $filters['amountMin'] ?? -INF;
            $max = $filters['amountMax'] ?? INF;
            $transactions = $this->filterByAmountRange($transactions, $min, $max);
        }

        if (count($transactions) == 0) {
            return [];
        }
        $transactions = $this->formatTransactions($transactions);


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
            'created_at' => $this->attributesMap['created_at'],
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

    /**
     * format transactions to unified format
     * @param array $transactions
     * @return array
     * 
     */
    public function formatTransactions($transactions): array
    {
        return array_map(function ($transaction) {
            return [
                'id' => $transaction[$this->getProviderTransactionsKeys()['id']],
                'amount' => $transaction[$this->getProviderTransactionsKeys()['amount']],
                'currency' => $transaction[$this->getProviderTransactionsKeys()['currency']],
                'created_at' => $transaction[$this->getProviderTransactionsKeys()['created_at']],
                'phone' => $transaction[$this->getProviderTransactionsKeys()['phone']],
                'status' => array_search($transaction[$this->getProviderTransactionsKeys()['status']], $this->getProviderTransactionsStatusMap()),
            ];
        }, $transactions);
    }
}
