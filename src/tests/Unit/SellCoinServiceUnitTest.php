<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\OpenWalletService;
use App\Http\Services\Adopter\SellCoinsAdapterService;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class SellCoinServiceUnitTest extends TestCase
{
    /**
     * @var SellCoinsAdapterService
     */
    private SellCoinsAdapterService $sellCoinsService;

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

        $this->sellCoinsService = new SellCoinsAdapterService($this->walletDataSource->reveal());
    }

    /**
     * @test
     */
    public function insertedWalletIdDoesNotExist_BadRequestIsGiven()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);
        $this->walletDataSource->insertTransaction('10','2','50000','1','50000','sell')->shouldBeCalledOnce()->willReturn(null);

        $this->expectExceptionMessage("wallet not found");
        $this->sellCoinsService->execute('90','2','50000','sell');
    }

}
