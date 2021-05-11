<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\GetWalletCryptocurrenciesService;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class GetCryptocurrenciesServiceUnitTest extends TestCase
{
    /**
     * @var GetWalletCryptocurrenciesService
     */
    private GetWalletCryptocurrenciesService $getWalletCryptocurrenciesService;

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

        $this->getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($this->walletDataSource->reveal());
    }

    /**
     * @test
     */
    public function insertedWalletIdDoesNotExist_BadRequestIsGiven()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);
        $this->walletDataSource->findWalletDataByWalletId('2')->shouldBeCalledOnce()->willReturn(null);
        $this->expectExceptionMessage("wallet not found");
        $this->getWalletCryptocurrenciesService->execute('2');
    }

    /**
     * @test
     */
    public function insertedWalletIdDoesExist_ExpectedOutputGiven()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "2"]);
        $expectedOutput = '{
            "id_transaction": 2,
            "id_coin": "90",
            "usd_buyed_amount": "50",
            "buyed_coins_amount": "0.00089988848581884",
            "buyed_coins_usd_price": "55562.44",
            "operation": "buy",
            "created_at": null,
            "updated_at": null,
            "id_wallet": 2
        }';

        $this->walletDataSource->findWalletDataByWalletId('2')->shouldBeCalledOnce()->willReturn($expectedOutput);
        $this->expectExceptionMessage("wallet not found");
        $this->getWalletCryptocurrenciesService->execute('2');
    }

}
