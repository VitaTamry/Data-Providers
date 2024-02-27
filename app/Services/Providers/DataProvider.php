<?php

namespace App\Services\Providers;

use stdClass;

interface DataProvider
{
    /**
     * @property $name
     */

    /**
     * @property $statusMap
     */
    /**
     * @property $attributesMap
     */


    public function getTransactions(array $filters): array;
    public function getProviderTransactionsKeys(): array;
    public function getProviderTransactionsStatusMap(): array;
    public function applyFilters($filters);
}
