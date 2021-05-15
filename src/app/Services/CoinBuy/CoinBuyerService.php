<?php


namespace App\Services\CoinBuy;


use App\DataSource\API\CoinLoreApi;
use App\DataSource\Database\EloquentCoinBuyerDataSource;
use App\DataSource\Database\EloquentUserDataSource;
use Exception;
use function PHPUnit\Framework\throwException;
use Illuminate\Http\Request;

class CoinBuyerService
{
    /**
     * @var EloquentUserDataSource
     */
    private $eloquentCoinBuyerDataSource;

    /**
     * IsEarlyAdopterService constructor.
     * @param EloquentCoinBuyerDataSource $eloquentCoinBuyerDataSource;
     */
    public function __construct(EloquentCoinBuyerDataSource $eloquentCoinBuyerDataSource)
    {
        $this->eloquentCoinBuyerDataSource = $eloquentCoinBuyerDataSource;
    }

    /**
     * @param string $email
     * @return bool
     * @throws Exception
     */
    public function execute($coin_id,$wallet_id,$amount_usd): bool
    {

        $wallet = $this->eloquentCoinBuyerDataSource->findWallet($wallet_id);
        if ($wallet != null) {
            throw new Exception("Error, wallet no encontrado");
        }

        $coinInfo = (new CoinLoreApi())->findCoinById($coin_id);
        try {
            $coin = $this->eloquentCoinBuyerDataSource->findCoin($coin_id);
            $this->eloquentCoinBuyerDataSource->updateCoin($coin_id,$coin->amount+$coin->amount/$coinInfo["price_usd"],$coin->amount+$amount_usd);
        } catch (Exception $exception) {
            $params = [$wallet_id,$coin_id,$coinInfo["name"],$coinInfo["name"],$amount_usd/$coinInfo["price_usd"] , $amount_usd];
            $this->eloquentCoinBuyerDataSource->insertCoin($params);
        }

        return true;
    }
}
