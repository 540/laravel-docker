<?php

namespace App\Services\SellCoinService;

use App\DataSource\API\CoinLoreCoinDataSource;
use App\DataSource\Database\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\CoinIdNotFoundInWalletException;
use Exception;

class SellCoinService
{
    private EloquentCoinDataSource $eloquentCoinDataSource;
    private EloquentWalletDataSource $eloquentWalletDataSource;
    private CoinLoreCoinDataSource $coinLoreCoinDataSource;

    public function __construct(EloquentCoinDataSource $eloquentCoinDataSource, EloquentWalletDataSource $eloquentWalletDataSource, CoinLoreCoinDataSource $coinLoreCoinDataSource)
    {
        $this->eloquentCoinDataSource = $eloquentCoinDataSource;
        $this->eloquentWalletDataSource = $eloquentWalletDataSource;
        $this->coinLoreCoinDataSource = $coinLoreCoinDataSource;
    }

    /**
     * @throws CoinIdNotFoundInWalletException
     * @throws Exception
     */
    public function execute(string $coinId, int $walletId, float $amountUSD)
    {
        $this->eloquentWalletDataSource->findWalletById($walletId);

        $coinInfo = $this->coinLoreCoinDataSource->findCoinById($coinId);

        $coin = $this->eloquentCoinDataSource->findCoinById($coinId, $walletId);

        $amountToSell = $amountUSD / $coinInfo['price_usd'];
d;
        if($coin->amount > $amountToSell)
            $this->eloquentCoinDataSource->sellCoinOperation($coin, ($coin->amount-$amountToSell), ($coin->value_usd-$amountUSD));
        else
            $this->eloquentCoinDataSource->deleteCoin($coin->id);
    }
}