<?php

namespace App\Services\Providers;

use App\Enums\TransactionStatusEnum;

abstract class DataProviderBase
{
    use TransactionFilterTrait;
    public $name;

    protected $transactions = [];
    protected $statusMap;

    protected $attributesMap;
    protected $filePath;

    /**
     * get all transactions then checks if there is a filter to apply
     * 
     * @param array $filter
     * @return array
     */
    public function getTransactions($filter): array
    {
        try {
            // @todo implement JsonMachine to handle large files
            $this->transactions = json_decode(file_get_contents(storage_path($this->filePath)), true);
        } catch (\Exception $e) {
            info($e->getMessage());
            return $this->transactions = [];
        }

        if (count($this->transactions) > 0 && $filter && count($filter) > 0) {
            $this->applyFilters($filter);
        }

        return $this->formatTransactions();
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
