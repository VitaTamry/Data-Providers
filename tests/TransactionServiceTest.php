<?php

namespace Tests;

use App\Services\TransactionsService;
use Illuminate\Http\Request;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransactionServiceTest extends TestCase
{

    /**
     * get all transactions from a all providers
     *
     * @return void
     */
    public function test_get_all_transactions_from_all_providers()
    {
        $request = [];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertCount(9, $transactions);
    }

    /**
     * get all transactions from a specific provider
     *
     * @return void
     */
    public function test_get_all_transactions_from_a_specific_provider()
    {
        $request = ['provider' => 'DataProviderX'];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertCount(3, $transactions);
    }


    /**
     *  It should be able to filter result by statusCode (paid, pending, reject) for example /api/v1/transactions?statusCode=paid.
     *  from a specific provider
     * @return void
     */
    public function test_filter_result_by_status_code_from_specific_provider()
    {
        $request = ['statusCode' => 'paid', 'provider' => 'DataProviderX'];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertCount(2, $transactions);
    }

    /**
     *  It should be able to filter result by statusCode (paid, pending, reject) for example /api/v1/transactions?statusCode=paid.
     *  from all providers
     * @return void
     */
    public function test_filter_result_by_status_code_from_all_providers()
    {
        $request = ['statusCode' => 'paid'];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertCount(4, $transactions);
    }

    /**
     * It should be able to filter by amount range for example /api/v1/transactions?amountMin=10&amountMax=100 it should return result between 10 and 100 including 10 and 100.
     * @return void
     */
    public function test_filter_by_amount_range()
    {
        $request = ['amountMin' => 10, 'amountMax' => 100];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertCount(4, $transactions);
        $this->assertThat(
            $transactions,
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['amount'] < 10 || $transaction['amount'] > 100) {
                            return false;
                        }
                    }
                    return true;
                })
            )
        );
    }
    /**
     * It should be able to filter by currency.
     * 
     */
    public function test_filter_by_currency()
    {
        $request = ['currency' => 'USD', 'provider' => 'DataProviderW'];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertCount(1, $transactions);
    }

    /**
     * It should be able to combine all these filters together.
     */
    public function test_combine_all_filters_together()
    {
        $request = ['statusCode' => 'paid', 'currency' => 'EGP', 'amountMin' => 10, 'amountMax' => 100];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertCount(1, $transactions);
    }
}
