<?php

namespace Tests\Integration\DataSources;

use App\DataSource\External\CoinLoreDataSource;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoinLoreDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @throws Exception
     */
    public function getUsdPriceByCoinIdCoinNotFound()
    {
        $expectedResult = null;

        $coinLoreDataSource = new CoinLoreDataSource();

        $result = $coinLoreDataSource->getUsdPriceByCoinId('error-coin');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getUsdPriceByCoinId()
    {
        $coinLoreDataSource = new CoinLoreDataSource();

        $result = $coinLoreDataSource->getUsdPriceByCoinId('2');
        $this->assertIsString($result);
    }
}
