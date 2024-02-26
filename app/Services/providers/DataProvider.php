<?php

namespace App\Services\Providers;

use stdClass;

interface DataProvider
{
    public function getTransactions(): DataProvider;
    public function getProviderTransactionsKeys(): array;
    public function getProviderTransactionsStatusMap(): array;
    public function applyFilters($request, $transactions): array;
}
