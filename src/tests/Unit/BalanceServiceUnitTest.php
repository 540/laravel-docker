<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\BalanceAdopterService;
use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class BalanceServiceUnitTest extends TestCase
{

    /**
     * @var WalletDataSource|\Prophecy\Prophecy\ObjectProphecy
     */
    private $walletDataSource;
    /**
     * @var BalanceAdopterService
     */
    private BalanceAdopterService $balanceService;
    /**
     * @var ApiSource|\Prophecy\Prophecy\ObjectProphecy
     */
    private $apiDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->walletDataSource = $prophet->prophesize(WalletDataSource::class);
        $this->apiDataSource = $prophet->prophesize(ApiSource::class);

        $this->balanceService = new BalanceAdopterService($this->walletDataSource->reveal(), $this->apiDataSource->reveal());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedWalletIdDoesNotExist_WalletNotFoundResponse()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);

        $this->walletDataSource->findTypeCoinsbyIdWallet('6')->shouldBeCalledOnce()->willReturn(null);

        $this->expectExceptionMessage("wallet not found");
        $this->balanceService->execute('6');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedWalletIdExists_NoTransactionsMade_EmptyWalletResponse()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);

        $this->walletDataSource->findTypeCoinsbyIdWallet('6')->shouldBeCalledOnce()->willReturn("[]");
        $this->walletDataSource->selectAmountBoughtCoins('90','6')->shouldBeCalledOnce()->willReturn(0);
        $this->walletDataSource->selectAmountSoldCoins('90', '6')->shouldBeCalledOnce()->willReturn(0);
        $this->apiDataSource->apiGetPrice('90')->shouldBeCalledOnce()->willReturn(50000);
        $this->balanceService->execute('6');
        $response = $this->balanceService->obtainBalance('90','6');

        $this->assertEquals(0,$response);

    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedWalletIdExists_NoTransactionsMade_ExpectedResponse()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);

        $this->walletDataSource->findTypeCoinsbyIdWallet('6')->shouldBeCalledOnce()->willReturn(1);
        $this->walletDataSource->selectAmountBoughtCoins('90','6')->shouldBeCalledOnce()->willReturn(8);
        $this->walletDataSource->selectAmountSoldCoins('90', '6')->shouldBeCalledOnce()->willReturn(5);
        $this->apiDataSource->apiGetPrice('90')->shouldBeCalledOnce()->willReturn(50000);
        $this->balanceService->execute('6');
        $response = $this->balanceService->obtainBalance('90','6');

        $this->assertEquals(150000,$response);

    }
}
