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

    /**
     * @test
     */
    public function walletDoesNotExist()
    {
        $walletId = 'error-wallet';
        $coinId = null;
        $expectedDatabaseReturn = null;
        $expectedExternalReturn = null;
        $expectedResult = "Wallet not found";

        $this->eloquentWalletDataSource
            ->findById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedDatabaseReturn);
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

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
    public function walletExistsButExternalAPIFails()
    {
        $walletId = 'test-wallet';
        $coinId = '90';
        $expectedDatabaseReturn = array(
            (object)[
                "coin_id" => $coinId,
                "name" => "Bitcoin",
                "symbol" => "BTC",
                "amount" => 2
            ]
        );
        $expectedExternalReturn = null;
        $expectedResult = "External API failure";

        $this->eloquentWalletDataSource
            ->findById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedDatabaseReturn);
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

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
    public function walletExists()
    {
        $walletId = 'test-wallet';
        $coinId = '90';
        $expectedDatabaseReturn = array(
            (object)[
                "coin_id" => $coinId,
                "name" => "Bitcoin",
                "symbol" => "BTC",
                "amount" => 2
            ]
        );
        $expectedExternalReturn = "5000";
        $expectedResult = array(array(
            "coin_id" => $coinId,
            "name" => "Bitcoin",
            "symbol" => "BTC",
            "amount" => 2,
            "value_usd" => 10000
        ));

        $this->eloquentWalletDataSource
            ->findById($walletId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedDatabaseReturn);
        $this->coinLoreDataSource
            ->findUsdPriceByCoinId($coinId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExternalReturn);

        try {
            $result = $this->walletService->execute($walletId);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertEquals($expectedResult, $result);
    }
}
