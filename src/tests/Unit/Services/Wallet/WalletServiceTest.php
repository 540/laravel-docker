<?php

namespace Tests\Unit\Services\Wallet;

use App\DataSource\Database\EloquentUserDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use App\Services\Wallet\WalletService;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class WalletServiceTest extends TestCase
{
    /**
     * @var EloquentWalletDataSource
     * @var EloquentUserDataSource
     * @var CoinLoreDataSource
     */
    private $eloquentWalletDataSource;
    private $eloquentUserDataSource;
    private $coinLoreDataSource;

    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->eloquentUserDataSource = $prophet->prophesize(EloquentUserDataSource::class);
        $this->coinLoreDataSource = $prophet->prophesize(CoinLoreDataSource::class);

        $this->walletService = new WalletService(
            $this->eloquentWalletDataSource->reveal(),
            $this->eloquentUserDataSource->reveal(),
            $this->coinLoreDataSource->reveal()
        );
    }

    // GET WALLET TESTS

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
            $this->walletService->execute($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
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
            $this->walletService->execute($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
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
            $result = $this->walletService->execute($walletId);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertEquals($expectedResult, $result);
    }

    // GET WALLET BALANCE TESTS

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
            $this->walletService->executeBalance($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
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
            $this->walletService->executeBalance($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
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
            $this->walletService->executeBalance($walletId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
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
            $result = $this->walletService->executeBalance($walletId);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertEquals($expectedResult, $result);
    }

    // POST WALLET OPEN TESTS

    /**
     * @test
     */
    public function postWalletOpenUserNotFound()
    {
        $userId = 'error-user';
        $expectedExistsByUserIdDatabaseUserReturn = false;
        $expectedCreateWalletByUserIdDatabaseWalletReturn = 'wallet-000000001';
        $expectedResult = "User not found";

        $this->eloquentUserDataSource
            ->existsByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByUserIdDatabaseUserReturn);
        $this->eloquentWalletDataSource
            ->createWalletByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCreateWalletByUserIdDatabaseWalletReturn);

        try {
            $this->walletService->executeOpen($userId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function postWalletOpenWorking()
    {
        $userId = 'test-user';
        $expectedExistsByUserIdDatabaseUserReturn = true;
        $expectedCreateWalletByUserIdDatabaseWalletReturn = 'wallet-000000001';
        $expectedResult = "wallet-000000001";

        $this->eloquentUserDataSource
            ->existsByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByUserIdDatabaseUserReturn);
        $this->eloquentWalletDataSource
            ->createWalletByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCreateWalletByUserIdDatabaseWalletReturn);

        try {
            $result = $this->walletService->executeOpen($userId);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertEquals($expectedResult, $result);
    }
}
