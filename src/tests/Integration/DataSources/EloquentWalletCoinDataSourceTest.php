<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentWalletCoinDataSource;
use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentWalletCoinDataSourceTest extends TestCase
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
    public function getAmountByIdsWalletNotFound()
    {
        $expectedResult = null;

        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        $result = $eloquentWalletCoinDataSource->getAmountByIds('2', 'error-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getAmountByIdsCoinNotFound()
    {
        $expectedResult = null;

        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        $result = $eloquentWalletCoinDataSource->getAmountByIds('error-coin', 'factory-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getAmountByIds()
    {
        $expectedResult = 1000000;

        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        $result = $eloquentWalletCoinDataSource->getAmountByIds('2', 'factory-wallet');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function buyCoins()
    {
        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        try {
            $eloquentWalletCoinDataSource->buyCoins(
                '2',
                'factory-wallet',
                '50',
                '100'
            );
        } catch (Exception $exception) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws Exception
     */
    public function sellCoinsInsufficientAmount()
    {
        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        try {
            $eloquentWalletCoinDataSource->sellCoins(
                '2',
                'factory-wallet',
                '10000000000',
                '100'
            );
        } catch (Exception $exception) {
            $this->assertEquals('Insufficient amount to sell', $exception->getMessage());
        }

        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws Exception
     */
    public function sellCoins()
    {
        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        try {
            $eloquentWalletCoinDataSource->sellCoins(
                '2',
                'factory-wallet',
                '50',
                '100'
            );
        } catch (Exception $exception) {
            $this->fail();
        }

        $this->assertTrue(true);
    }
}
