<?php


namespace App\Services\WalletCryptocurrencies;


use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\WalletNotFoundException;

class GetWalletCryptocurrenciesService
{
    private EloquentWalletDataSource $eloquentWalletDataSource;

    public function __construct(EloquentWalletDataSource $eloquentWalletCoinDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentWalletCoinDataSource;
    }

    /**
     * @throws WalletNotFoundException
     */
    public function execute($walletId): array
    {
        $wallet = $this->eloquentWalletDataSource->findWalletById($walletId);
        return $this->getFormattedCoins($wallet);
    }

    private function getFormattedCoins($wallet): array
    {
        $formattedCoins = [];
        foreach ($wallet->coins as $coin){
            array_push($formattedCoins,[
                'coin_id' => $coin->coin_id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]);
        }
        return $formattedCoins;
    }
}
