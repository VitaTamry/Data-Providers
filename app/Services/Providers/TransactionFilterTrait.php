<?php

namespace App\Services\Providers;

trait TransactionFilterTrait
{

    private $transactions = [];

    /**
     * get all transactions then checks if there is a filter to apply
     * 
     * @param array $filter
     * @return array
     */
    public function getTransactions($filter): array
    {
        try {
            $this->transactions = json_decode(file_get_contents(storage_path($this->filePath)), true);
        } catch (\Exception $e) {
            info($e->getMessage());
            $this->transactions = [];
        }

        if (count($this->transactions) > 0 && $filter && count($filter) > 0) {
            $this->applyFilters($filter);
        }

        return $this->formatTransactions();
    }


    public function applyFilters($filters)
    {
        if (isset($filters['statusCode'])) {
            $this->transactions = $this->filterByStatus($this->transactions, $filters['statusCode']);
        }
        if (isset($filters['currency'])) {
            $this->transactions = $this->filterByCurrency($this->transactions, $filters['currency']);
        }
        if (isset($filters['amountMin']) || isset($filters['amountMax'])) {
            $min = $filters['amountMin'] ?? -INF;
            $max = $filters['amountMax'] ?? INF;
            $this->transactions = $this->filterByAmountRange($this->transactions, $min, $max);
        }
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
        return $this->attributesMap;
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
    public function formatTransactions(): array
    {
        return array_map(function ($transaction) {
            return [
                'id' => $transaction[$this->getProviderTransactionsKeys()['id']],
                'amount' => $transaction[$this->getProviderTransactionsKeys()['amount']],
                'currency' => $transaction[$this->getProviderTransactionsKeys()['currency']],
                'created_at' => $transaction[$this->getProviderTransactionsKeys()['created_at']],
                'phone' => $transaction[$this->getProviderTransactionsKeys()['phone']],
                'status' => array_search($transaction[$this->getProviderTransactionsKeys()['status']], $this->getProviderTransactionsStatusMap()),
                'provider' => $this->name,
            ];
        }, $this->transactions);
    }
}
