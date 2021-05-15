<?php

namespace Tests\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinSellerDataSource;
use App\Models\Coin;
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
        $eloquentCoinSellerDataSource = $this->prophet->prophesize(EloquentCoinSellerDataSource::class);
        $eloquentCoinSellerDataSource->findCoinById($coinId, $walletId)
            ->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinSellerDataSource->reveal());

        $this->expectException(Exception::class);

        $sellCoinService->execute($coinId, $walletId, $amountUSD);
    }

    /**
     * @test
     */
    public function coinIsFoundIfCoinIdIsCorrect()
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

        $eloquentCoinSellerDataSource = $this->prophet
            ->prophesize(EloquentCoinSellerDataSource::class);
        $eloquentCoinSellerDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $sellCoinService = new SellCoinService($eloquentCoinSellerDataSource->reveal());

        $returnedCoin = $sellCoinService->execute($coin->coin_id, $coin->wallet_id, 1);

        $this->assertEquals($coin->coin_id, $returnedCoin->coin_id);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsExceptionIfCannotSellPartOfTheCoinsForGivenCorrectId()
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
        $amountUSD = 1;
        $newCoinAmount = 1;

        $eloquentCoinSellerDataSource = $this->prophet
            ->prophesize(EloquentCoinSellerDataSource::class);
        $eloquentCoinSellerDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinSellerDataSource
            ->sellCoinOperation($coin, $coin->wallet_id, $newCoinAmount)
            ->shouldBeCalledOnce()
            ->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinSellerDataSource->reveal());
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

        $eloquentCoinSellerDataSource = $this->prophet
            ->prophesize(EloquentCoinSellerDataSource::class);
        $eloquentCoinSellerDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinSellerDataSource
            ->sellCoinOperation($coin, $coin->wallet_id, $newCoinAmount)
            ->shouldBeCalledOnce()
            ->will(function () use ($coin, $newCoinAmount) {
                DB::table('coins')
                    ->where('coin_id', $coin->coin_id)
                    ->where('wallet_id', $coin->wallet_id)
                    ->update(['amount' => $newCoinAmount]);
            });
        $sellCoinService = new SellCoinService($eloquentCoinSellerDataSource->reveal());

        $sellCoinService->execute($coin->coin_id, $coin->wallet_id, 1);
        $updatedCoin = DB::table('coins')->where('id', $coin->id)->first();

        $this->assertEquals($expectedAmount, $updatedCoin->amount);
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

        $eloquentCoinSellerDataSource = $this->prophet
            ->prophesize(EloquentCoinSellerDataSource::class);
        $eloquentCoinSellerDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinSellerDataSource
            ->deleteCoin($coin)
            ->shouldBeCalledOnce()
            ->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinSellerDataSource->reveal());
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

        $eloquentCoinSellerDataSource = $this->prophet
            ->prophesize(EloquentCoinSellerDataSource::class);
        $eloquentCoinSellerDataSource
            ->findCoinById($coin->coin_id, $coin->wallet_id)
            ->shouldBeCalledOnce()
            ->willReturn($coin);
        $eloquentCoinSellerDataSource
            ->deleteCoin($coin->id)
            ->shouldBeCalledOnce()
            ->will(function () use ($coin) {
                DB::table('coins')->where('id', $coin->id)->delete();
            });
        $sellCoinService = new SellCoinService($eloquentCoinSellerDataSource->reveal());

        $sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);

        $deletedCoin = DB::table('coins')->where('id', $coin->id)->first();

        $this->assertNull($deletedCoin);
    }
}
