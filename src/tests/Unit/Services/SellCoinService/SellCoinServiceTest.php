<?php

namespace Tests\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinDataSource;
use App\Models\Coin;
use App\Models\Wallet;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Prophecy\Prophet;

class SellCoinServiceTest extends TestCase
{
    use RefreshDatabase;

    private Prophet $prophet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->prophet = new Prophet;
    }

    /**
     * @test
     */
    public function coinIsNotFoundIfCoinIdIsNotCorrect()
    {
        $coinId = "invalidCoinId";
        $walletId = 1;
        $amountUSD = 0;
        $eloquentCoinDataSource = $this->prophet
            ->prophesize(EloquentCoinDataSource::class);
        $eloquentCoinDataSource->findCoinById($coinId, $walletId)
            ->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinDataSource->reveal());

        $this->expectException(Exception::class);

        $sellCoinService->execute($coinId, $walletId, $amountUSD);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsExceptionIfCannotSellPartOfTheCoinsForGivenCorrectId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();
        $expectedCoin = [];
        array_push($expectedCoin, [
            'wallet_id' => $coin->wallet_id,
            'coin_id' => $coin->coin_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $coin->amount,
            'value_usd' => $coin->value_usd
        ]);
        $amountUSD = 1;
        $newCoinAmount = 0.5;

        $eloquentCoinDataSource = $this->prophet
            ->prophesize(EloquentCoinDataSource::class);
        $eloquentCoinDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinDataSource
            ->sellCoinOperation($coin, $coin->wallet_id, $newCoinAmount)
            ->shouldBeCalledOnce()
            ->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinDataSource->reveal());
        $this->expectException(Exception::class);

        $sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);
    }

    /**
     * @test
     */
    public function sellsPartOfTheCoinsForGivenId()
    {
        $coin = Coin::factory()->create()->first();
        $expectedCoin = [];
        array_push($expectedCoin, [
            'wallet_id' => $coin->wallet_id,
            'coin_id' => $coin->coin_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $coin->amount,
            'value_usd' => $coin->value_usd
        ]);
        $newCoinAmount = 1;
        $expectedAmount = 1;

        $eloquentCoinDataSource = $this->prophet
            ->prophesize(EloquentCoinDataSource::class);
        $eloquentCoinDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinDataSource
            ->sellCoinOperation($coin, $coin->wallet_id, $newCoinAmount)
            ->shouldBeCalledOnce()
            ->will(function () use ($coin, $newCoinAmount) {
                DB::table('coins')
                    ->where('coin_id', $coin->coin_id)
                    ->where('wallet_id', $coin->wallet_id)
                    ->update(['amount' => $newCoinAmount]);
            });
        $sellCoinService = new SellCoinService($eloquentCoinDataSource->reveal());

        $sellCoinService->execute($coin->coin_id, $coin->wallet_id, 1);
        $updatedSoldCoin = DB::table('coins')->where('id', $coin->id)->first();

        $this->assertEquals($expectedAmount, $updatedSoldCoin->amount);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsExceptionIfCannotSellEveryCoinForGivenCorrectId()
    {
        $coin = Coin::factory()->create()->first();
        $expectedCoin = [];
        array_push($expectedCoin, [
            'wallet_id' => $coin->wallet_id,
            'coin_id' => $coin->coin_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $coin->amount,
            'value_usd' => $coin->value_usd
        ]);
        $amountUSD = 2;

        $eloquentCoinDataSource = $this->prophet
            ->prophesize(EloquentCoinDataSource::class);
        $eloquentCoinDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinDataSource
            ->deleteCoin($coin)
            ->shouldBeCalledOnce()
            ->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinDataSource->reveal());
        $this->expectException(Exception::class);

        $sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);
    }

    /**
     * @test
     */
    public function sellsEveryCoinForGivenId()
    {
        $coin = Coin::factory()->create()->first();
        $expectedCoin = [];
        array_push($expectedCoin, [
            'wallet_id' => $coin->wallet_id,
            'coin_id' => $coin->coin_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $coin->amount,
            'value_usd' => $coin->value_usd
        ]);
        $amountUSD = 2;

        $eloquentCoinDataSource = $this->prophet
            ->prophesize(EloquentCoinDataSource::class);
        $eloquentCoinDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinDataSource
            ->deleteCoin($coin->id)
            ->shouldBeCalledOnce()
            ->will(function () use ($coin) {
                DB::table('coins')->where('id', $coin->id)->delete();
            });
        $sellCoinService = new SellCoinService($eloquentCoinDataSource->reveal());

        $sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);

        $deletedSoldCoin = DB::table('coins')->where('id', $coin->id)->first();

        $this->assertNull($deletedSoldCoin);
    }
}
