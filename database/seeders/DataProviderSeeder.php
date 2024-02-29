<?php

namespace Database\Seeders;

use App\Services\Providers\DataProvider;
use Illuminate\Database\Seeder;
use Faker\Factory;

class DataProviderSeeder extends Seeder
{

    private $currencies = ['USD', 'EUR', 'EGP'];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fileSize  = 1000;
        $providers = ['DataProviderX', 'DataProviderY', 'DataProviderW'];
        foreach ($providers as $provider) {
            $providerClassName = 'App\\Services\\Providers\\' . $provider;
            if (!class_exists($providerClassName)) {
                continue;
            }
            $providerClass = new $providerClassName;
            if ($provider == 'DataProviderX') {
                $transactions = $this->createDataProviderX($fileSize, $providerClass);
            } else
                $transactions = $this->createDataProviderYW($fileSize, $providerClass);
            $filePath = storage_path('app/providers/' . $provider . '.json');
            file_put_contents($filePath, json_encode($transactions));
        }
    }

    /**
     * faker fo DataProviderX
     * @return array
     */
    private function createDataProviderX($count, DataProvider $provider): array
    {
        $statusMap = $provider->getProviderTransactionsStatusMap();
        $transactions = [];
        $faker = Factory::create();
        for ($i = 0; $i < $count; $i++) {
            $transactions[] = [

                'transactionAmount' =>  $faker->randomFloat(2, 100, 1000),
                'Currency' => $this->currencies[array_rand($this->currencies)],
                'senderPhone' => '00201234567890',
                'transactionStatus' => $statusMap[array_rand($statusMap)],
                'transactionDate' => '2021-03-29 09:36:11',
                'transactionIdentification' => 'd3d29d70-1d25-11e3-8591-034165a3a613'

            ];
        }
        return $transactions;
    }

    /**
     * faker fo DataProviderY and DataProviderY
     * @return array
     */
    private function createDataProviderYW($count, DataProvider $provider): array
    {
        $statusMap = $provider->getProviderTransactionsStatusMap();
        $faker = Factory::create();
        $transactions = [];
        for ($i = 0; $i < $count; $i++) {
            $transactions[] = [
                'amount' => $faker->randomFloat(2, 100, 1000),
                'currency' =>  $this->currencies[array_rand($this->currencies)],
                'phone' => $faker->phoneNumber,
                'status' => $statusMap[array_rand($statusMap)],
                'created_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'id' => $faker->uuid
            ];
        }
        return $transactions;
    }
}
