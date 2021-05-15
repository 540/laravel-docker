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
            'coinId' => $coinId,
            'walletId' => $walletId,
            'amountUSD' => $amountUSD
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson([400 => "Bad request error"]);
    }

    /**
     * @test
     */
    public function sellsCoinForGivenId()
    {
        $coin = Coin::factory(Coin::class)->create()->first();

        $response = $this->postJson('/api/coin/sell', [
            'coinId' => $coin->id,
            'walletId' => $coin->wallet_id,
            'amountUSD' => 1
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([200 => "Successful operation"]);
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
            $coinIdField => 'coinId',
            'walletId' => $walletId,
            'amountUSD' => $amountUSD
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson([404 => "A coin with specified ID was not found"]);
    }
}
