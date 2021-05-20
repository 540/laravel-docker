<?php

namespace Tests\Unit\Controllers;

use App\DataSource\API\CoinDataSource;
use App\Http\Controllers\SellCoinController;
use App\Models\Coin;
use App\Models\Wallet;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Prophecy\Prophet;
use Tests\Integration\Controller\Doubles\FakeCoinLoreDataSource;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    use RefreshDatabase;

    private SellCoinController $sellCoinController;

    /**
     * @test
     */
    public function getsHttpNotFoundForInvalidCoinId()
    {
        $coinId = "invalidCoinId";
        $walletId = 1;
        $amountUSD = 1;

        $response = $this->postJson('api/coin/sell', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => $amountUSD
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson([404 => "A coin with specified ID was not found"]);
    }

    /**
     * @test
     */
    public function sellsPartOfTheCoinsForGivenCoinId()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSource::class);

        $wallet = Wallet::factory()->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('coin_id',$coin->coin_id)->first();

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => $coin->coin_id,
            'wallet_id' => $coin->wallet_id,
            'amount_usd' => 0.5
        ]);
        $returnedCoin = Coin::query()
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $coin->wallet_id)
            ->first();

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([200 => "Successful operation"]);
        $this->assertEquals(0.5, $returnedCoin->amount);
    }

    /**
     * @test
     */
    public function sellsEveryCoinForGivenCoinId()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSource::class);

        $wallet = Wallet::factory()->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('coin_id',$coin->coin_id)->first();

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => $coin->coin_id,
            'wallet_id' => $coin->wallet_id,
            'amount_usd' => 2
        ]);
        $deletedCoin = Coin::query()
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $coin->wallet_id)
            ->first();

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([200 => "Successful operation"]);
        $this->assertEquals(null, $deletedCoin);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsHttpBadRequestIfCoinIdFieldIsNotFound()
    {
        $walletId = 1;
        $amountUSD = 1;

        $response = $this->postJson('/api/coin/sell', [
            'coin' => 'coin_id',
            'wallet' => $walletId,
            'amount_usd' => $amountUSD
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson([400 => "Bad request error"]);
    }
}
