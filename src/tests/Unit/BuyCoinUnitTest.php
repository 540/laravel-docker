<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\BuyCoinsAdapterService;
use App\Http\Services\Adopter\OpenWalletService;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class BuyCoinUnitTest extends TestCase
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
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->walletDataSource = $prophet->prophesize(WalletDataSource::class);
        $this->walletDataSource = $prophet->prophesize(WalletDataSource::class);

        $this->buyCoinsService = new BuyCoinsAdapterService($this->walletDataSource->reveal());
    }

    /**
     * @test
     */
    public function insertedCoinIdDoesNotExist_BadRequestIsGiven()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);
        $this->walletDataSource->insertTransaction('90','2','50000','1','50000','buy')->shouldBeCalledOnce()->willReturn(null);
        try {
            $this->buyCoinsService->execute('90','2','50000','1','50000','buy');
        }catch (\Exception $exception) {
            $this->assertEquals("wallet not found",$exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function insertedWalletIdDoesNotExist_BadRequestIsGiven()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);
        $this->walletDataSource->insertTransaction('10','2','50000','1','50000','buy')->shouldBeCalledOnce()->willReturn(null);
        $this->expectExceptionMessage("wallet not found");
        $this->buyCoinsService->execute('90','2','50000','buy');
    }

}
