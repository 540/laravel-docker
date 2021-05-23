<?php


namespace App\Services\CoinBuy;


use App\DataSource\API\CoinDataSource;
use App\DataSource\Database\EloquentCoinDataSource;
use App\DataSource\Database\EloquentUserDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\CannotCreateACoinException;
use App\Exceptions\CannotUpdateACoinException;
use App\Exceptions\CoinIdNotFoundInWalletException;
use App\Exceptions\WalletNotFoundException;

class CoinBuyerService
{
    /**
     * @var EloquentUserDataSource
     */
    private $eloquentCoinDataSource;
    private EloquentWalletDataSource $eloquentWalletDataSource;
    private CoinDataSource $coinLoreCoinDataSource;

    /**
     * @param EloquentCoinDataSource $eloquentCoinDataSource
     * @param EloquentWalletDataSource $eloquentWalletDataSource
     * @param CoinDataSource $coinLoreCoinDataSource
     */
    public function __construct(EloquentCoinDataSource $eloquentCoinDataSource,EloquentWalletDataSource $eloquentWalletDataSource,CoinDataSource $coinLoreCoinDataSource)
    {
        $this->coinLoreCoinDataSource = $coinLoreCoinDataSource;
        $this->eloquentCoinDataSource = $eloquentCoinDataSource;
        $this->eloquentWalletDataSource = $eloquentWalletDataSource;
    }

    /**
     * @param $coin_id
     * @param $wallet_id
     * @param $amount_usd
     * @throws CannotCreateACoinException
     * @throws CannotUpdateACoinException
     * @throws WalletNotFoundException
     */
    public function execute($coin_id,$wallet_id,$amount_usd): void
    {
        $this->eloquentWalletDataSource->findWalletById($wallet_id);
        $coinInfo = $this->coinLoreCoinDataSource->findCoinById($coin_id);

        try {
            $coin = $this->eloquentCoinDataSource->findCoinById($coin_id, $wallet_id);
            $this->eloquentCoinDataSource->updateCoin($wallet_id, $coin_id, ($coin->amount + ($amount_usd/ $coinInfo["price_usd"])), ($coin->value_usd + $amount_usd));
        } catch (CoinIdNotFoundInWalletException $exception) {
            $params = [$wallet_id, $coin_id, $coinInfo['name'], $coinInfo['symbol'], ($amount_usd / $coinInfo['price_usd']), $amount_usd];
            $this->eloquentCoinDataSource->insertCoin($params);
        }
    }
}
