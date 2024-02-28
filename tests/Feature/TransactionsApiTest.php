<?php

namespace Tests\Feature;

use Tests\TestCase;

class TransactionsApiTest extends TestCase
{

    /**
     * get all transactions from a all providers
     *
     * @return void
     */
    public function test_get_all_transactions_from_all_providers()
    {
        $response = $this->call('GET', '/api/v1/transactions');
        $response->assertStatus(200);
        $transactions = $response->json();
        $this->assertIsArray($transactions);
        $this->assertCount(9, $transactions['payload']);
    }

    /**
     * get all transactions from a specific provider
     *
     * @return void
     */
    public function test_get_all_transactions_from_a_specific_provider()
    {
        $response = $this->call('GET', '/api/v1/transactions?', ['provider' => 'DataProviderX']);
        $response->assertStatus(200);
        $transactions = $response->json();
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions['payload'],
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
        $statusCode = 'paid';
        $response = $this->call('GET', '/api/v1/transactions', ['statusCode' => $statusCode, 'provider' => 'DataProviderX']);
        $response->assertStatus(200);
        $transactions = $response->json();
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions['payload'],
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) use ($statusCode) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['status'] != $statusCode || $transaction['provider'] != 'DataProviderX') {
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
        $response = $this->call('GET', '/api/v1/transactions', ['statusCode' => 'paid']);
        $response->assertStatus(200);
        $transactions = $response->json();
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions['payload'],
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
        $response = $this->call('GET', '/api/v1/transactions', ['amountMin' => 10, 'amountMax' => 100]);
        $response->assertStatus(200);
        $transactions = $response->json();
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions['payload'],
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
        $response = $this->call('GET', '/api/v1/transactions', ['currency' => 'USD']);
        $response->assertStatus(200);
        $transactions = $response->json();
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions['payload'],
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if ($transaction['currency'] != 'USD') {
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
        $response = $this->call('GET', '/api/v1/transactions', [
            'statusCode' => 'paid',
            'amountMin' => 10,
            'amountMax' => 200,
            'currency' => 'USD',
            'provider' => 'DataProviderX'
        ]);
        $response->assertStatus(200);
        $transactions = $response->json();
        $this->assertIsArray($transactions);
        $this->assertThat(
            $transactions['payload'],
            $this->logicalAnd(
                $this->isType('array'),
                $this->callback(function ($transactions) {
                    foreach ($transactions as $transaction) {
                        if (
                            $transaction['status'] != 'paid'
                            || $transaction['amount'] < 10
                            || $transaction['amount'] > 200
                            || $transaction['currency'] != 'USD'
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
}
