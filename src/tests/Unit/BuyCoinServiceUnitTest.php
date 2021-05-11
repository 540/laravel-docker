<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\BalanceAdopterService;
use App\Http\Services\Adopter\BuyCoinsAdapterService;
use App\Http\Services\Adopter\OpenWalletService;
use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Cryptocurrencies;
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

        $this->apiDataSource->apiConnection("12345")->willReturn(0);
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

        $this->walletDataSource->insertTransaction('10','2','50000','1','50000','buy')->shouldBeCalledOnce()->willReturn(-1);
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
        $currency = new Cryptocurrencies();
        $spected = $currency->fill([
            "id"=>"90",
            "symbol"=>"BTC",
            "name"=>"Bitcoin",
            "nameid"=>"bitcoin",
            "rank"=>1,
            "price_usd"=>"56555.91",
            "percent_change_24h"=>"-2.01",
            "percent_change_1h"=>"0.13",
            "percent_change_7d"=>"0.31",
            "market_cap_usd"=>"1055472018549.00",
            "volume24"=>"93618535433.66",
            "volume24_native"=>"1655327.09",
            "csupply"=>"18662452.00",
            "price_btc"=>"1.00",
            "tsupply"=>"18662452",
            "msupply"=>"21000000"]);

        $this->walletDataSource->insertTransaction('90','2','50000','1','50000','buy')->shouldBeCalledOnce()->willReturn(1);
        $this->apiDataSource->apiConnection("90")->shouldBeCalledOnce()->willReturn($spected);
        $response = $this->buyCoinsService->execute('90','2','50000','buy');

        $this->assertEquals("Successful Operation", $response);
    }

}
