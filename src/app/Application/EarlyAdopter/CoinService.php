<?php

namespace App\Application\EarlyAdopter;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\CoinDataSource\CryptoCoinDataSource;
use Exception;


class CoinService
{
    /**
     * @var CoinDataSource
     */
    private $coinDataSource;

    /**
     * @param CoinDataSource $coinDataSource
     */
    public function __construct(CoinDataSource $coinDataSource)
    {
        $this->coinDataSource = $coinDataSource;
    }

    /**use App\Domain\Coin;
 * @param string $coin_id
     * @return \App\Domain\Coin
     * @throws Exception
     */
    public function execute(string $coin_id): \App\Domain\Coin
    {
        //Llamar a la api con el coin_id
        $coin = $this->coinDataSource->findByCoinId($coin_id);
        return $coin;
    }
}
