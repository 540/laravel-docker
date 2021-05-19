<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentCoinDataSource;
use App\Models\Coin;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentCoinDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function doesNotFindCoinIfCoinIdIsIncorrect() {
        Coin::factory(Coin::class)->create();
        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $this->expectException(Exception::class);

        $coin = $eloquentCoinDataSource->findCoinById('1', 1);
    }

    /**
     * @test
     * @throws Exception
     */
    public function findsCoinIfCoinIdIsCorrect() {
        Coin::factory(Coin::class)->create();
        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $coin = $eloquentCoinDataSource->findCoinById('btc', 1);

        $this->assertInstanceOf(Coin::class, $coin);
    }

    /**
     * @test
     */
    public function doesNotSellCoinIfCoinIdIsIncorrect() {
        Coin::factory(Coin::class)->create();
        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $this->expectException(Exception::class);

        $eloquentCoinDataSource->sellCoinOperation('1', 1, 0);
    }

    /**
     * @test
     * @throws Exception
     */
    public function sellsCoinIfCoinIdIsCorrect() {
        $coin = Coin::factory(Coin::class)->create()->first();
        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $eloquentCoinDataSource->sellCoinOperation($coin, 1, 1);
        $coin = Coin::query()
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $coin->wallet_id)
            ->first();
        $expectedCoinAmount = 1;
        $leftCoinAmount = $coin->amount;

        $this->assertEquals($expectedCoinAmount, $leftCoinAmount);
    }

    /**
     * @test
     */
    public function doesNotDeleteCoinIfCoinIdIsIncorrect() {
        Coin::factory(Coin::class)->create();
        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $this->expectException(Exception::class);

        $eloquentCoinDataSource->deleteCoin('invalidId');
    }

    /**
     * @test
     * @throws Exception
     */
    public function deletesCoinIfCoinIdIsCorrect() {
        $coin = Coin::factory(Coin::class)->create()->first();
        $eloquentCoinDataSource = new EloquentCoinDataSource();

        $eloquentCoinDataSource->deleteCoin(1);
        $coin = Coin::query()
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $coin->wallet_id)
            ->first();
        $expectedCoin = null;

        $this->assertEquals($expectedCoin, $coin);
    }
}
