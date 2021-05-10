<?php

namespace Tests\Unit\Services\WalletCryptocurrencies;

use App\DataSource\Database\EloquentWalletCoinDataSource;
use App\Models\Coin;
use App\Models\Wallet;
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
        $eloquentWalletCoinDataSource->findWalletById($walletId)->willThrow(Exception::class);

        $getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($eloquentWalletCoinDataSource->reveal());

        $this->expectException(Exception::class);

        $getWalletCryptocurrenciesService->execute($walletId);
    }

    /**
     * @test
     **/
    public function aCoinIsFoundForAGivenWalletId()
    {
        $wallet = Wallet::factory()->create()->first();
        $walletId = $wallet->id;

        $coin = Coin::factory()->create();
        $wallet->coins()->attach($coin, ['amount' => 1, 'value_usd' => 1]);

        $expectedResult = [];
        foreach ($wallet->coins as $coin){
            array_push($expectedResult, [
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->pivot->amount,
                'value_usd' => $coin->pivot->value_usd
            ]);
        }

        $eloquentWalletCoinDataSource = $this->prophet->prophesize(EloquentWalletCoinDataSource::class);
        $eloquentWalletCoinDataSource->findWalletById($walletId)->shouldBeCalledOnce()->willReturn($wallet);

        $getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($eloquentWalletCoinDataSource->reveal());

        $result = $getWalletCryptocurrenciesService->execute($walletId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     **/
    public function twoCoinsAreFoundForAGivenWalletId()
    {
        $wallet = Wallet::factory()->create()->first();
        $walletId = $wallet->id;

        $coins = Coin::factory()->count(2)->create();
        foreach ($coins as $coin){
            $wallet->coins()->attach($coin, ['amount' => 1, 'value_usd' => 1]);
        }

        $expectedResult = [];
        foreach ($wallet->coins as $coin){
            array_push($expectedResult, [
                    'coin_id' => $coin->id,
                    'name' => $coin->name,
                    'symbol' => $coin->symbol,
                    'amount' => $coin->pivot->amount,
                    'value_usd' => $coin->pivot->value_usd
            ]);
        }

        $eloquentWalletCoinDataSource = $this->prophet->prophesize(EloquentWalletCoinDataSource::class);
        $eloquentWalletCoinDataSource->findWalletById($walletId)->shouldBeCalledOnce()->willReturn($wallet);

        $getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($eloquentWalletCoinDataSource->reveal());

        $result = $getWalletCryptocurrenciesService->execute($walletId);

        $this->assertEquals($expectedResult, $result);
    }
}
