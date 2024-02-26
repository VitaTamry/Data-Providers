<?php

namespace App\Services\providers;

interface DataProvider
{
    public function getTransactions($request): array;
    public function formatTransactionsKeys($transactions): array;
    public function applyFilters($request, $transactions): array;
}
