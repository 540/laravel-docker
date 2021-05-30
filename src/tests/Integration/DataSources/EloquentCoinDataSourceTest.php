<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentCoinDataSource;
use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentCoinDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        User::factory(User::class)->create();
        Wallet::factory(Wallet::class)->create();
        Coin::factory(Coin::class)->create();
        WalletCoin::factory(WalletCoin::class)->create();
    }

    /**
     * @test
     * @throws Exception
     */
    public function notExistsByCoinId()
    {
        $expectedResult = false;

        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $result = $eloquentCoinDataSource->existsByCoinId('error-coin');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function existsByCoinId()
    {
        $expectedResult = true;

        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $result = $eloquentCoinDataSource->existsByCoinId('2');
        $this->assertEquals($expectedResult, $result);
    }
}
