<?php


namespace App\Services\WalletCryptocurrencies;


use App\DataSource\Database\EloquentWalletDataSource;
use Exception;
use function PHPUnit\Framework\isNull;

class GetWalletCryptocurrenciesService
{
    private EloquentWalletDataSource $eloquentWalletDataSource;

    public function __construct(EloquentWalletDataSource $eloquentWalletCoinDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentWalletCoinDataSource;
    }


    public function execute($walletId): array
    {
        $wallet = $this->eloquentWalletDataSource->findWalletById($walletId);

        $coins = [];

        foreach ($wallet->coins as $coin){
            array_push($coins,[
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]);
        }

        return $coins;
    }
}
