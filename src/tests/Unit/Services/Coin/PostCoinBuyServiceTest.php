<?php

namespace Tests\Unit\Services\Coin;

use App\DataSource\Database\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use App\Services\Coin\PostCoinBuyService;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class PostCoinBuyServiceTest extends TestCase
{
    /**
     * @var EloquentCoinDataSource
     * @var EloquentWalletDataSource
     * @var EloquentWalletCoinDataSource
     * @var CoinLoreDataSource
     */
    private $eloquentCoinDataSource;
    private $eloquentWalletDataSource;
    private $eloquentWalletCoinDataSource;
    private $coinLoreDataSource;

    /**
     * @var PostCoinBuyService
     */
    private $postCoinBuyService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->eloquentCoinDataSource = $prophet->prophesize(EloquentCoinDataSource::class);
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->eloquentWalletCoinDataSource = $prophet->prophesize(EloquentWalletCoinDataSource::class);
        $this->coinLoreDataSource = $prophet->prophesize(CoinLoreDataSource::class);

        $this->postCoinBuyService = new PostCoinBuyService(
            $this->eloquentCoinDataSource->reveal(),
            $this->eloquentWalletDataSource->reveal(),
            $this->eloquentWalletCoinDataSource->reveal(),
            $this->coinLoreDataSource->reveal()
        );
    }

    /**
     * @test
     */
    public function postCoinBuyCoinNotFound()
    {
        $coinId = 'error-coin';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedExistsByCoinIdDatabaseCoinReturn = false;
        $expectedExistsByWalletIdDatabaseWalletReturn = true;
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = 5000;
        $expectedResult = "Coin not found";

        $this->eloquentCoinDataSource
            ->existsByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByCoinIdDatabaseCoinReturn);
        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);

        try {
            $this->postCoinBuyService->execute($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function postCoinBuyWalletNotFound()
    {
        $coinId = '90';
        $walletId = 'error-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedExistsByCoinIdDatabaseCoinReturn = true;
        $expectedExistsByWalletIdDatabaseWalletReturn = false;
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = 5000;
        $expectedResult = "Wallet not found";

        $this->eloquentCoinDataSource
            ->existsByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByCoinIdDatabaseCoinReturn);
        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);

        try {
            $this->postCoinBuyService->execute($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function postCoinBuyExternalAPIFails()
    {
        $coinId = '90';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedExistsByCoinIdDatabaseCoinReturn = true;
        $expectedExistsByWalletIdDatabaseWalletReturn = true;
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = null;
        $expectedResult = "External API failure";

        $this->eloquentCoinDataSource
            ->existsByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByCoinIdDatabaseCoinReturn);
        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);

        try {
            $this->postCoinBuyService->execute($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function postCoinBuyWorking()
    {
        $coinId = '90';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedExistsByCoinIdDatabaseCoinReturn = true;
        $expectedExistsByWalletIdDatabaseWalletReturn = true;
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = 5000;

        $this->eloquentCoinDataSource
            ->existsByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByCoinIdDatabaseCoinReturn);
        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);

        try {
            $this->postCoinBuyService->execute($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertTrue(true);
    }
}
