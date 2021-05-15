<?php

namespace Tests\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinSellerDataSource;
use App\Models\Coin;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * @throws Exception
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
}
