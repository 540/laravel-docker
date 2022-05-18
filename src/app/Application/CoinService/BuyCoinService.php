<?php
namespace App\Application\CoinService;

use App\Application\CoinDataSource\BuyCoinDataSource;
use App\Application\CoinDataSource\BuyCoinDataSourceFunction;

use App\Domain\Coin;

use Exception;
use Illuminate\Http\JsonResponse;


class BuyCoinService
{
    /**
     * @var BuyCoinDataSource
     */
    private BuyCoinDataSource $BuyCoinDataSource;

    /**
     * @param BuyCoinDataSource $BuyCoinDataSource
     */
    public function __construct(BuyCoinDataSource $BuyCoinDataSource)
    {
        $this->BuyCoinDataSource = $BuyCoinDataSource;
    }

    /**use App\Domain\Coin;
     * @param string $coin_id
     * @return Coin|Exception
     * @throws Exception
     */
    public function execute(string $coin_id,string $wallet_id,float $amount_usd): Coin|Exception
    {
        //Llamar a la api con el coin_id
        return $this->BuyCoinDataSource->findByCoinId($coin_id,$wallet_id,$amount_usd);
    }
}
