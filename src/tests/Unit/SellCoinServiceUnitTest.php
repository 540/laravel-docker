<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\OpenWalletService;
use App\Http\Services\Adopter\SellCoinsAdapterService;
use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use PhpParser\Node\Expr\Cast\Object_;
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

        $this->sellCoinsService = new SellCoinsAdapterService($this->walletDataSource->reveal(), $this->apiDataSource->reveal());
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

        $this->walletDataSource->selectAmountBoughtCoins('90','6')->shouldBeCalledOnce()->willReturn(-1);

        $this->expectExceptionMessage("wallet not found");
        $this->sellCoinsService->execute('90','6','50000','sell');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedWalletIdExists_NotEnoughCoinsResponse()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);
        $boughtCoins = 8;
        $soldCoins = 5;
        $wantToSellAmount = 6;

        $this->walletDataSource->selectAmountBoughtCoins('90','6')->shouldBeCalledOnce()->willReturn($boughtCoins);
        $this->walletDataSource->selectAmountSoldCoins('90','6')->shouldBeCalledOnce()->willReturn($soldCoins);

        $this->expectExceptionMessage("not enough coins to sell");
        $this->sellCoinsService->execute('90','6',$wantToSellAmount,'sell');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedWalletIdExists_WrongParameterCoinInsteadOfDollars_TransactionErrorResponse()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);
        $boughtCoins = 8;
        $soldCoins = 5;
        $wantToSellAmount = 1;

        $this->walletDataSource->selectAmountBoughtCoins('90','6')->shouldBeCalledOnce()->willReturn($boughtCoins);
        $this->walletDataSource->selectAmountSoldCoins('90','6')->shouldBeCalledOnce()->willReturn($soldCoins);
        $this->walletDataSource->insertTransaction('90','6',$wantToSellAmount,$boughtCoins,'50000','sell')->shouldBeCalledOnce()->willReturn(null);

        $this->expectExceptionMessage("transaction error");
        $this->sellCoinsService->execute('90','6', $wantToSellAmount,'sell');

        //$this->assertEquals("successful operation", $buyCoinsResponse);
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
        $boughtCoins = 8;
        $soldCoins = 5;
        $wantToSellAmount = 1;

        $this->walletDataSource->selectAmountBoughtCoins('90','6')->shouldBeCalledOnce()->willReturn($boughtCoins);
        $this->walletDataSource->selectAmountSoldCoins('90','6')->shouldBeCalledOnce()->willReturn($soldCoins);
        $this->apiDataSource->apiGetPrice("90")->shouldBeCalledOnce()->willReturn(50000);
        $this->walletDataSource->insertTransaction('90','6','50000', $wantToSellAmount,'50000','sell')->shouldBeCalledOnce()->willReturn(1);

        $buyCoinsResponse = $this->sellCoinsService->execute('90','6', $wantToSellAmount,'sell');

        $this->assertEquals("successful operation", $buyCoinsResponse);
    }
}
