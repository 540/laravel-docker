<?php

namespace Tests\Unit\Services\Wallet;

use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use App\Services\Wallet\GetWalletBalanceService;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class GetWalletBalanceServiceTest extends TestCase
{
    /**
     * @var EloquentWalletDataSource
     * @var CoinLoreDataSource
     */
    private $eloquentWalletDataSource;
    private $coinLoreDataSource;

    /**
     * @var GetWalletBalanceService
     */
    private $getWalletBalanceService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->coinLoreDataSource = $prophet->prophesize(CoinLoreDataSource::class);

        $this->getWalletBalanceService = new GetWalletBalanceService(
            $this->eloquentWalletDataSource->reveal(),
            $this->coinLoreDataSource->reveal()
        );
    }

    /**
     * @test
     */
    public function getWalletBalanceWalletNotFound()
    {
        $walletId = 'error-wallet';
        $coinId = '90';
        $expectedExistsByWalletIdDatabaseWalletReturn = false;
        $expectedGetCoinsDataByWalletIdDatabaseWalletReturn = null;
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = "5000";
        $expectedGetBalanceByIdDatabaseWalletReturn = -2000;
        $expectedResult = "Wallet not found";

        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletDataSource
            ->getCoinsDataByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetCoinsDataByWalletIdDatabaseWalletReturn);
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);
        $this->eloquentWalletDataSource
            ->getBalanceUsdByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetBalanceByIdDatabaseWalletReturn);

        try {
            $this->getWalletBalanceService->execute($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function getWalletBalanceExternalAPIFails()
    {
        $walletId = 'test-wallet';
        $coinId = '90';
        $expectedExistsByWalletIdDatabaseWalletReturn = true;
        $expectedGetCoinsDataByWalletIdDatabaseWalletReturn = array(
            (object)[
                "coin_id" => $coinId,
                "name" => "Bitcoin",
                "symbol" => "BTC",
                "amount" => 2
            ]
        );
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = null;
        $expectedWalletBalanceByIdDatabaseReturn = -2000;
        $expectedResult = "External API failure";

        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletDataSource
            ->getCoinsDataByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetCoinsDataByWalletIdDatabaseWalletReturn);
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);
        $this->eloquentWalletDataSource
            ->getBalanceUsdByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedWalletBalanceByIdDatabaseReturn);

        try {
            $this->getWalletBalanceService->execute($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function getWalletBalanceBalanceNotFound()
    {
        $walletId = 'test-wallet';
        $coinId = '90';
        $expectedExistsByWalletIdDatabaseWalletReturn = true;
        $expectedGetCoinsDataByWalletIdDatabaseWalletReturn = array(
            (object)[
                "coin_id" => $coinId,
                "name" => "Bitcoin",
                "symbol" => "BTC",
                "amount" => 2
            ]
        );
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = '5000';
        $expectedGetBalanceByIdDatabaseWalletReturn = null;
        $expectedResult = "Wallet balance not found";

        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletDataSource
            ->getCoinsDataByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetCoinsDataByWalletIdDatabaseWalletReturn);
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);
        $this->eloquentWalletDataSource
            ->getBalanceUsdByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetBalanceByIdDatabaseWalletReturn);

        try {
            $this->getWalletBalanceService->execute($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function getWalletBalanceWorking()
    {
        $walletId = 'error-wallet';
        $coinId = '90';
        $expectedExistsByWalletIdDatabaseWalletReturn = true;
        $expectedGetCoinsDataByWalletIdDatabaseWalletReturn = array(
            (object)[
                "coin_id" => $coinId,
                "name" => "Bitcoin",
                "symbol" => "BTC",
                "amount" => 2
            ]
        );
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = '5000';
        $expectedGetBalanceByIdDatabaseWalletReturn = -2000;
        $expectedResult = 8000;

        $this->eloquentWalletDataSource
            ->existsByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByWalletIdDatabaseWalletReturn);
        $this->eloquentWalletDataSource
            ->getCoinsDataByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetCoinsDataByWalletIdDatabaseWalletReturn);
        $this->coinLoreDataSource
            ->getUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn);
        $this->eloquentWalletDataSource
            ->getBalanceUsdByWalletId($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedGetBalanceByIdDatabaseWalletReturn);

        try {
            $result = $this->getWalletBalanceService->execute($walletId);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertEquals($expectedResult, $result);
    }
}
