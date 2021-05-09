<?php

namespace Tests\Unit\Services\WalletCryptocurrencies;

use App\DataSource\Database\EloquentWalletCoinDataSource;
use App\Models\Coin;
use App\Models\WalletCoin;
use App\Services\WalletCryptocurrencies\GetWalletCryptocurrenciesService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\Prophet;
use Tests\TestCase;

class GetWalletCryptocurrenciesServiceTest extends TestCase
{
    use RefreshDatabase;

    private Prophet $prophet;

    protected function setUp():void
    {
        parent::setUp();
        $this->prophet = new Prophet();
    }


    /**
     * @test
     **/
    public function noCoinsFoundForAGivenWalletId()
    {
        $walletId = '1';

        $eloquentWalletCoinDataSource = $this->prophet->prophesize(EloquentWalletCoinDataSource::class);
        $eloquentWalletCoinDataSource->findWalletCoins($walletId)->willThrow(Exception::class);

        $getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($eloquentWalletCoinDataSource->reveal());

        $this->expectException(Exception::class);

        $getWalletCryptocurrenciesService->execute($walletId);
    }

    /**
     * @test
     **/
    public function aCoinIsFoundForAGivenWalletId()
    {
        $walletId = '1';

        $eloquentWalletCoinDataSource = $this->prophet->prophesize(EloquentWalletCoinDataSource::class);
        $eloquentWalletCoinDataSource->findWalletCoins($walletId)->shouldBeCalledOnce();

        $getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($eloquentWalletCoinDataSource->reveal());

        $coin = Coin::factory(Coin::class)->create()->first();
        $walletCoin = WalletCoin::factory(WalletCoin::class)->create()->first();
        $result= $getWalletCryptocurrenciesService->execute($walletId);

        $expectedResult = [
            'coin_id' => $walletCoin->coin_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $walletCoin->amount,
            'value_usd' => $walletCoin->value_usd
        ];
        $this->assertEquals($expectedResult, $result);
    }
}
