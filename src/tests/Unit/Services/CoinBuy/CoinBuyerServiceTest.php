<?php

namespace Tests\Unit\Services\CoinBuy;

use App\DataSource\API\CoinLoreApi;
use App\DataSource\API\EloquentCoinDataSource;
use App\DataSource\Database\EloquentCoinBuyerDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletBalance\GetWalletBalanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class CoinBuyerServiceTest extends TestCase
{
    use RefreshDatabase;
    private Prophet $prophet;

    protected function setUp():void
    {
        parent::setUp();
        $this->prophet = new Prophet();
    }

    public function execute($coin_id,$wallet_id,$amount_usd): bool
    {
        //Que ocurre si se lanza una exception en el DS?
        $this->eloquentCoinBuyerDataSource->findWallet($wallet_id);
        //Si la exception pasa al Controller, todo funciona correctamente

        $coinInfo = (new CoinLoreApi())->findCoinById($coin_id);
        try {
            $coin = $this->eloquentCoinBuyerDataSource->findCoin($coin_id);
            $this->eloquentCoinBuyerDataSource->updateCoin($coin_id,$coin->amount+$coin->amount/$coinInfo["price_usd"],$coin->amount+$amount_usd);
        } catch (Exception $exception) {
            $params = [$wallet_id,$coin_id,$coinInfo["name"],$coinInfo["name"],$amount_usd/$coinInfo["price_usd"] , $amount_usd];
            $this->eloquentCoinBuyerDataSource->insertCoin($params);
        }

        return true;
    }
    /**
     * @test
     **/
    public function coinIsFound ()
    {
        $walletId = 1;

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentCoinBuyerDataSource::class);
        $eloquentWalletDataSource->findWallet($walletId)->shouldBeCalledOnce()->willThrow(Exception::class);

        $eloquentCoinDataSource = $this->prophet->prophesize(EloquentCoinDataSource::class);

        $getWalletBalanceService = new GetWalletBalanceService($eloquentWalletDataSource->reveal(), $eloquentCoinDataSource->reveal());

        $this->expectException(Exception::class);

        $getWalletBalanceService->execute($walletId);
    }

    /**
     * @test
     **/
    public function coinIsNotFound()
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
