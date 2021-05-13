<?php

namespace Tests\Unit\Services\WalletBalance;

use App\DataSource\API\CoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\WrongCoinIdException;
use App\Models\Coin;
use App\Models\Wallet;
use App\Services\WalletBalance\GetWalletBalanceService;
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
        $eloquentWalletDataSource->findWalletById($walletId)->shouldBeCalledOnce()->willThrow(WalletNotFoundException::class);

        $coinDataSource = $this->prophet->prophesize(CoinDataSource::class);

        $getWalletBalanceService = new GetWalletBalanceService($eloquentWalletDataSource->reveal(), $coinDataSource->reveal());

        $this->expectException(WalletNotFoundException::class);

        $getWalletBalanceService->execute($walletId);
    }

    /**
     * @test
     **/
    public function coinIsNotFoundGivenAWrongCoinId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $coin = Coin::factory(Coin::class)->make();

        $wallet->coins()->save($coin);

        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $coinDataSource = $this->prophet->prophesize(CoinDataSource::class);
        $coinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willThrow(WrongCoinIdException::class);

        $getWalletBalanceService = new GetWalletBalanceService($eloquentWalletDataSource->reveal(), $coinDataSource->reveal());

        $this->expectException(WrongCoinIdException::class);

        $getWalletBalanceService->execute($wallet->id);
    }

    /**
     * @test
     **/
    public function BalanceIsProvidedForAGivenWalletId()
    {
        $wallet = Wallet::factory()->create()->first();

        $coins = Coin::factory(Coin::class)->count(2)->make();

        $wallet->coins()->saveMany($coins);

        $coins = Coin::query()->where('wallet_id', $wallet->id)->get();

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $coinDataSource = $this->prophet->prophesize(CoinDataSource::class);

        $expectedResult = 0;

        foreach ($coins as $coin)
        {
            $coinDataSource->findCoinById($coin->coin_id)->willReturn([
                'price_usd' => 30
            ]);
            $expectedResult += 30 - ($coin->amount * $coin->value_usd);
        }

        $getWalletBalanceService = new GetWalletBalanceService($eloquentWalletDataSource->reveal(), $coinDataSource->reveal());

        $result = $getWalletBalanceService->execute($wallet->id);

        $this->assertEquals($expectedResult, $result);
    }
}
