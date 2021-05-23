<?php

namespace Tests\Integration\DataSources\API;

use App\DataSource\API\CoinLoreCoinDataSource;
use App\Exceptions\WrongCoinIdException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CoinLoreCoinDataSourceTest extends TestCase
{
    /**
     * @test
     **/
    public function noCoinIsFoundInCoinLoreAPIGivenAWrongCoinId()
    {
        Http::fake([
            'https://api.coinlore.net/*' => Http::response(null, 404)
            ]);

        $CoinLoreCoinDataSource = new CoinLoreCoinDataSource();

        $this->expectException(WrongCoinIdException::class);

        $coinId = 'wrongCoinId';
        $CoinLoreCoinDataSource->findCoinById($coinId);
    }

    /**
     * @test
     **/
    public function coinIsFoundInCoinLoreAPIGivenAValidCoinId()
    {
        $expectedResponse = [
            'price_usd' => 1
        ];

        Http::fake([
            'https://api.coinlore.net/*' => Http::response([$expectedResponse], 200)
        ]);

        $CoinLoreCoinDataSource = new CoinLoreCoinDataSource();

        $coinId = 'validCoinId';
        $response = $CoinLoreCoinDataSource->findCoinById($coinId);

        $this->assertEquals($expectedResponse, $response);
    }
}
