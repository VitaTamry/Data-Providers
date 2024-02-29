<?php

namespace App\Services\Providers;

use \JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

abstract class DataProviderBase implements DataProvider
{
    use TransactionFilterTrait;
    public $name;

    protected $transactions = [];
    protected $statusMap;

    protected $attributesMap = [
        'amount' => 'amount',
        'currency' => 'currency',
        'created_at' => 'created_at',
        'id' => 'id',
        'phone' => 'phone',
        'status' => 'status'
    ];
    protected $filePath;
    protected $chunkSize = 100;


    /**
     * get all transactions then checks if there is a filter to apply
     * 
     * @param array $filter
     * @return array
     */
    public function getTransactions($filter): array
    {
        try {

            $transactionsStream = Items::fromFile(storage_path($this->filePath), ['decoder' => new ExtJsonDecoder(true)]);
        } catch (\Exception $e) {
            info($e->getMessage());
            return $this->transactions = [];
        }

        $transactionsChunk = [];

        foreach ($transactionsStream as $transaction) {

            // save each 100 transactions to chucks bag
            array_push($transactionsChunk, $transaction);

            if (count($transactionsChunk) == $this->chunkSize) {

                // if there's a filter apply it to the chunk
                if ($filter && count($filter) > 0) {
                    $filterdTransactions = $this->applyFilters($filter, $transactionsChunk);

                    array_map(function ($filterdTransaction) use (&$count) {
                        array_push($this->transactions, $filterdTransaction);
                    }, $filterdTransactions);
                } else {
                    array_map(function ($transaction) use (&$count) {
                        array_push($this->transactions, $transaction);
                    }, $transactionsChunk);
                }
                // reset the chunk
                $transactionsChunk = [];
            }
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
