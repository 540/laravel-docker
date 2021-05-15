<?php

namespace Tests\Unit\Services\WalletBalance;

use App\DataSource\API\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletBalance\GetWalletBalanceService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\Prophet;
use Tests\TestCase;

class GetWalletBalanceServiceTest extends TestCase
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
    public function noWalletIsFoundForAGivenWalletId()
    {
        $walletId = 1;

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->findWalletById($walletId)->shouldBeCalledOnce()->willThrow(Exception::class);

        $eloquentCoinDataSource = $this->prophet->prophesize(EloquentCoinDataSource::class);

        $getWalletBalanceService = new GetWalletBalanceService($eloquentWalletDataSource->reveal(), $eloquentCoinDataSource->reveal());

        $this->expectException(Exception::class);

        $getWalletBalanceService->execute($walletId);
    }

    /**
     * @test
     **/
    public function noCoinIsFoundForAGivenCoinId()
    {
        $user = User::factory(User::class)->create()->first();

        $wallet = Wallet::factory(Wallet::class)->make();

        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id)->first();

        $coin = Coin::factory(Coin::class)->make();

        $wallet->coins()->save($coin);

        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $eloquentCoinDataSource = $this->prophet->prophesize(EloquentCoinDataSource::class);
        $eloquentCoinDataSource->findCoinById($coin->id)->shouldBeCalledOnce()->willReturn(null);


        $getWalletBalanceService = new GetWalletBalanceService($eloquentWalletDataSource->reveal(), $eloquentCoinDataSource->reveal());

        $this->expectException(Exception::class);
        $getWalletBalanceService->execute($wallet->id);
    }

    /**
     * @test
     **/
    public function BalanceIsProvidedForAGivenWalletId()
    {
        $user = User::factory()->create()->first();

        $wallet = Wallet::factory()->make();

        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id)->first();

        $coins = Coin::factory(Coin::class)->count(2)->make();

        $wallet->coins()->saveMany($coins);

        $coins = Coin::query()->where('wallet_id', $wallet->id)->get();

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $eloquentCoinDataSource = $this->prophet->prophesize(EloquentCoinDataSource::class);

        $expectedResult = 0;

        foreach ($coins as $coin){
            $eloquentCoinDataSource->findCoinById($coin->coin_id)->willReturn([
                'price_usd' => 30
            ]);

            $expectedResult += 30 - ($coin->amount * $coin->value_usd);
        }

        $getWalletBalanceService = new GetWalletBalanceService($eloquentWalletDataSource->reveal(), $eloquentCoinDataSource->reveal());

        $result = $getWalletBalanceService->execute($wallet->id);

        $this->assertEquals($expectedResult, $result);
    }
}
