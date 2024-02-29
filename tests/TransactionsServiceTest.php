<?php

namespace Tests;

use App\Services\TransactionsService;


class TransactionsServiceTest extends TestCase
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
        // $this->assertCount(9, $transactions);
        $this->assertJson(json_encode($transactions));
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
        // $this->assertCount(3, $transactions);
        $this->assertThat(
            $transactions,
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['provider'] != 'DataProviderX') {
                            return false;
                        }
                    }
                    return true;
                })
            )
        );
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
        $this->assertThat(
            $transactions,
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['status'] != 'paid' || $transaction['provider'] != 'DataProviderX') {
                            return false;
                        }
                    }
                    return true;
                })
            )
        );
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
        $this->assertThat(
            $transactions,
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['status'] != 'paid') {
                            return false;
                        }
                    }
                    return true;
                })
            )
        );
    }

    /**
     * It should be able to filter by amount range for example /api/v1/transactions?amountMin=10&amountMax=100 it should return result between 10 and 100 including 10 and 100.
     * @return void
     */
    public function test_filter_by_amount_range()
    {
        $request = ['amountMin' => 190, 'amountMax' => 400];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions,
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['amount'] <= 190 || $transaction['amount'] >=  403) {
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
        $request = ['currency' => 'USD', 'provider' => 'DataProviderX'];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions,
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if (
                            $transaction['currency'] != 'USD'
                            || $transaction['provider'] != 'DataProviderX'
                        ) {
                            return false;
                        }
                    }
                    return true;
                })
            )
        );
    }

    /**
     * It should be able to combine all these filters together.
     */
    public function test_combine_all_filters_together()
    {
        $request = ['statusCode' => 'paid', 'currency' => 'EGP', 'amountMin' => 100, 'amountMax' => 500];
        $transactionsService = new TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions,
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if (
                            $transaction['status'] != 'paid'
                            || $transaction['currency'] != 'EGP'
                            || $transaction['amount'] < 100
                            || $transaction['amount'] > 500
                        ) {
                            return false;
                        }
                    }
                    return true;
                })
            )
        );
    }
}
