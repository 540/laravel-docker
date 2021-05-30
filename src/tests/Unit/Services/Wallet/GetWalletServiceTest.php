<?php

namespace Tests\Unit\Services\Wallet;

use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use App\Services\Wallet\GetWalletService;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class GetWalletServiceTest extends TestCase
{
    /**
     * @var EloquentWalletDataSource
     * @var CoinLoreDataSource
     */
    private $eloquentWalletDataSource;
    private $coinLoreDataSource;

    /**
     * @var GetWalletService
     */
    private $getWalletService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->coinLoreDataSource = $prophet->prophesize(CoinLoreDataSource::class);

        $this->getWalletService = new GetWalletService(
            $this->eloquentWalletDataSource->reveal(),
            $this->coinLoreDataSource->reveal()
        );
    }

    /**
     * @test
     */
    public function getWalletWalletNotFound()
    {
        $walletId = 'error-wallet';
        $coinId = '90';
        $expectedExistsByWalletIdDatabaseWalletReturn = false;
        $expectedGetCoinsDataByWalletIdDatabaseWalletReturn = null;
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = "5000";
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

        try {
            $this->getWalletService->execute($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function getWalletExternalAPIFails()
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

        try {
            $this->getWalletService->execute($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function getWalletWorking()
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
        $expectedGetUsdPriceByCoinIdDatabaseCoinLoreReturn = "5000";
        $expectedResult = array(array(
            "coin_id" => $coinId,
            "name" => "Bitcoin",
            "symbol" => "BTC",
            "amount" => 2,
            "value_usd" => 10000
        ));

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

        try {
            $result = $this->getWalletService->execute($walletId);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertEquals($expectedResult, $result);
    }
}
