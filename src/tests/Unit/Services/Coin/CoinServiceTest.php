<?php

namespace Tests\Unit\Services\Coin;

use App\DataSource\Database\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use App\Services\Coin\CoinService;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class CoinServiceTest extends TestCase
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
     * @var CoinService
     */
    private $coinService;

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

        $this->coinService = new CoinService(
            $this->eloquentCoinDataSource->reveal(),
            $this->eloquentWalletDataSource->reveal(),
            $this->eloquentWalletCoinDataSource->reveal(),
            $this->coinLoreDataSource->reveal()
        );
    }

    /**
     * @test
     */
    public function buyCoinDoesNotExist()
    {
        $coinId = 'error-coin';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = false;
        $expectedWalletDatabaseReturn = true;
        $expectedExternalReturn = 5000;
        $expectedResult = "Coin not found";

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeBuy($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function sellCoinDoesNotExist()
    {
        $coinId = 'error-coin';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = false;
        $expectedWalletDatabaseReturn = true;
        $expectedExternalReturn = 5000;
        $expectedResult = "Coin not found";

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->sellCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeSell($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function buyWalletDoesNotExist()
    {
        $coinId = '90';
        $walletId = 'error-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = true;
        $expectedWalletDatabaseReturn = false;
        $expectedExternalReturn = 5000;
        $expectedResult = "Wallet not found";

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeBuy($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function sellWalletDoesNotExist()
    {
        $coinId = '90';
        $walletId = 'error-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = true;
        $expectedWalletDatabaseReturn = false;
        $expectedExternalReturn = 5000;
        $expectedResult = "Wallet not found";

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->sellCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeSell($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function buyExternalAPIDoesNotWork()
    {
        $coinId = '90';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = true;
        $expectedWalletDatabaseReturn = true;
        $expectedExternalReturn = null;
        $expectedResult = "External API failure";

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeBuy($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function sellExternalAPIDoesNotWork()
    {
        $coinId = '90';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = true;
        $expectedWalletDatabaseReturn = true;
        $expectedExternalReturn = null;
        $expectedResult = "External API failure";

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->sellCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeSell($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function buyWorking()
    {
        $coinId = '90';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = true;
        $expectedWalletDatabaseReturn = true;
        $expectedExternalReturn = 5000;

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->buyCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeBuy($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function sellWorking()
    {
        $coinId = '90';
        $walletId = 'test-wallet';
        $amountUsd = 5000;
        $amount = 1;
        $expectedCoinDatabaseReturn = true;
        $expectedWalletDatabaseReturn = true;
        $expectedExternalReturn = 5000;

        $this->eloquentCoinDataSource
            ->thereIsCoinById($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCoinDatabaseReturn);
        $this->eloquentWalletDataSource
            ->thereIsWalletById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletDatabaseReturn);
        $this->eloquentWalletCoinDataSource
            ->sellCoins($coinId, $walletId, $amount, $amountUsd)
            ->shouldBeCalledOnce();
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $this->coinService->executeSell($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertTrue(true);
    }
}
