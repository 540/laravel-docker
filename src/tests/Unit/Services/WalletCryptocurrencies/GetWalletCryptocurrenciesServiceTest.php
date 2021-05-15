<?php

namespace Tests\Unit\Services\WalletCryptocurrencies;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\Coin;
use App\Models\User;
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

        $eloquentWalletCoinDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
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
        $user = User::factory()->create()->first();
        $wallet = Wallet::factory()->make();

        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id)->first();

        $coins = Coin::factory(Coin::class)->make();

        $wallet->coins()->save($coins);

        $expectedResult = [];
        foreach ($wallet->coins as $coin){
            array_push($expectedResult, [
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]);
        }

        $eloquentWalletCoinDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletCoinDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($eloquentWalletCoinDataSource->reveal());

        $result = $getWalletCryptocurrenciesService->execute($wallet->id);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     **/
    public function twoCoinsAreFoundForAGivenWalletId()
    {
        $user = User::factory()->create()->first();
        $wallet = Wallet::factory()->make();

        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id)->first();

        $coins = Coin::factory(Coin::class)->count(2)->make();

        $wallet->coins()->saveMany($coins);

        $expectedResult = [];
        foreach ($wallet->coins as $coin){
            array_push($expectedResult, [
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]);
        }

        $eloquentWalletCoinDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletCoinDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($eloquentWalletCoinDataSource->reveal());

        $result = $getWalletCryptocurrenciesService->execute($wallet->id);

        $this->assertEquals($expectedResult, $result);
    }
}
