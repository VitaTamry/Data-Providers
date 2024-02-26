<?php

namespace Tests;

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
        $request = new Request();
        $transactionsService = new \App\Services\TransactionsService();
        $transactions = $transactionsService->getTransactions($request);
        info((string) $transactions);
        $this->assertIsArray($transactions);
        $this->assertCount(3, $transactions);
    }
}
