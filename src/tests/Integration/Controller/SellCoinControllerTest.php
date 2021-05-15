<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\SellCoinController;
use App\Models\Coin;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Prophecy\Prophet;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    use RefreshDatabase;

    private SellCoinController $sellCoinController;

    /**
     * @test
     */
    public function getsHttpNotFoundWhenInvalidCoinIdIsReceived()
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
    public function sellsOneOfMoreCoinsForGivenId()
    {
        $coin = Coin::factory(Coin::class)->create()->first();

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => $coin->coin_id,
            'wallet_id' => $coin->wallet_id,
            'amount_usd' => 1
        ]);
        $returnedCoin = Coin::query()
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $coin->wallet_id)
            ->first();

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([200 => "Successful operation"]);
        $this->assertEquals(1, $returnedCoin->amount);
    }

    /**
     * @test
     */
    public function sellsEveryCoinForGivenId()
    {
        $coin = Coin::factory(Coin::class)->create()->first();

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
    public function getsHttpBadRequestWhenCoinIdFieldIsNotFound()
    {
        $coinIdField = "";
        $walletId = 1;
        $amountUSD = 1;

        $response = $this->postJson('/api/coin/sell', [
            $coinIdField => 'coin_id',
            'wallet_id' => $walletId,
            'amount_usd' => $amountUSD
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson([400 => "Bad request error"]);
    }
}
