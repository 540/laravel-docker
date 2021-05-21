<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\BalanceAdopterService;
use App\Http\Services\Adopter\BuyCoinsAdapterService;
use App\Http\Services\Adopter\OpenWalletService;
use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Transaction;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class BuyCoinServiceUnitTest extends TestCase
{

    /**
     * @var BuyCoinsAdapterService
     */
    private BuyCoinsAdapterService $buyCoinsService;

    /**
     * @var OpenWalletService|WalletDataSource|\Prophecy\Prophecy\ObjectProphecy
     */
    private $walletDataSource;
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

        $this->buyCoinsService = new BuyCoinsAdapterService($this->walletDataSource->reveal(), $this->apiDataSource->reveal());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedCoinIdDoesNotExist_BadRequestIsGiven()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);

        $this->apiDataSource->apiGetPrice("12345")->willReturn(0);
        $this->walletDataSource->insertTransaction('90','2','50000','1','50000','buy')->shouldBeCalledOnce()->willReturn(null);

        $this->expectExceptionMessage("coin does not exist");
        $this->buyCoinsService->execute('90','2','50000','buy');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedWalletIdDoesNotExist_BadRequestIsGiven()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);

        $this->apiDataSource->apiGetPrice("90")->willReturn(50000);
        $this->walletDataSource->insertTransaction('90','2','50000','1','50000','buy')->shouldBeCalledOnce()->willReturn(-1);

        $this->expectExceptionMessage("wallet not found");
        $this->buyCoinsService->execute('90','2','50000','buy');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedWalletIdExists_ExpectedResponse()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);

        $this->apiDataSource->apiGetPrice("90")->shouldBeCalledOnce()->willReturn(50000);
        $this->walletDataSource->insertTransaction('90','2','50000','1','50000','buy')->shouldBeCalledOnce()->willReturn(1);
        $response = $this->buyCoinsService->execute('90','2','50000','buy');

        $this->assertEquals("Successful Operation", $response);
    }

}
