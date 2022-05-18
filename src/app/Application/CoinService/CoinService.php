<?php

namespace App\Application\CoinService;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\CoinDataSource\CryptoCoinDataSource;
use App\Domain\Coin;
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
     * @return Coin|Exception
     * @throws Exception
     */
    public function execute(string $coin_id)
    {
        $coin = $this->coinDataSource->findByCoinId($coin_id);
        return $coin;
    }
}
