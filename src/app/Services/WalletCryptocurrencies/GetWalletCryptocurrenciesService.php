<?php


namespace App\Services\WalletCryptocurrencies;


use App\DataSource\Database\EloquentWalletCoinDataSource;
use Exception;
use function PHPUnit\Framework\isNull;

class GetWalletCryptocurrenciesService
{
    private EloquentWalletCoinDataSource $eloquentWalletDataSource;

    public function __construct(EloquentWalletCoinDataSource $eloquentWalletCoinDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentWalletCoinDataSource;
    }


    public function execute($walletId): array
    {
        $wallet = $this->eloquentWalletDataSource->findWalletById($walletId);
        if($wallet == null){
            throw new Exception('a wallet with the specified ID was not found.');
        }

        $coins = [];

        foreach ($wallet->coins as $coin){
            array_push($coins,[
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->pivot->amount,
                'value_usd' => $coin->pivot->value_usd
            ]);
        }

        return $coins;
    }
}
