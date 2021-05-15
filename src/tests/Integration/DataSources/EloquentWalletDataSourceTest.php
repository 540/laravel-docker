<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentWalletDataSourceTest extends TestCase
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
    public function getCoinsDataByWalletIdWalletNotFound()
    {
        $expectedResult = array();

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletDataSource->getCoinsDataByWalletId('error-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getCoinsDataByWalletId()
    {
        $expectedResult = array(
            (object)[
                "coin_id" => '2',
                "name" => "Dogecoin",
                "symbol" => "DOGE",
                "amount" => 1000000
            ]
        );

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletDataSource->getCoinsDataByWalletId('factory-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getBalanceUsdByWalletIdWalletNotFound()
    {
        $expectedResult = null;

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletDataSource->getBalanceUsdByWalletId('error-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getBalanceUsdByWalletId()
    {
        $expectedResult = 0;

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletDataSource->getBalanceUsdByWalletId('factory-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function notExistsByWalletId()
    {
        $expectedResult = false;

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletDataSource->existsByWalletId('error-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function existsByWalletId()
    {
        $expectedResult = true;

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletDataSource->existsByWalletId('factory-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function createWalletByUserId()
    {
        $expectedResult = 'wallet-000000001';

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletDataSource->createWalletByUserId('factory-user');
        $this->assertEquals($expectedResult, $result);
    }
}
