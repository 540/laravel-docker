<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class PostCoinSellControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        User::factory(User::class)->create();
        Wallet::factory(Wallet::class)->create();
        Coin::factory(Coin::class)->create();
        WalletCoin::factory(WalletCoin::class)->create();
    }

    /**
     * @test
     */
    public function postCoinSellCoinNotFound()
    {
        $response = $this->postJson(
            '/api/coin/sell',
            [
                "coin_id" => "error-coin",
                "wallet_id" => "factory-wallet",
                "amount_usd" => 50000
            ]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'status' => 'Coin with the specified ID was not found',
            'message' => 'Coin not found'
        ]);
    }

    /**
     * @test
     */
    public function postCoinSellWalletNotFound()
    {
        $response = $this->postJson(
            '/api/coin/sell',
            [
                "coin_id" => "2",
                "wallet_id" => "error-wallet",
                "amount_usd" => 1
            ]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'status' => 'Wallet with the specified ID was not found',
            'message' => 'Wallet not found'
        ]);
    }

    /**
     * @test
     */
    public function postCoinSellInsufficientAmount()
    {
        $response = $this->postJson(
            '/api/coin/sell',
            [
                "coin_id" => "2",
                "wallet_id" => "factory-wallet",
                "amount_usd" => 0
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'status' => 'Bad Request Error',
            'message' => 'Insufficient amount to sell'
        ]);
    }

    /**
     * @test
     */
    public function postCoinSellInsufficientArguments()
    {
        $response = $this->postJson(
            '/api/coin/sell',
            [
                "coin_id" => "2",
                "wallet_id" => "factory-wallet"
            ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            'status' => 'Bad Request Error',
            'message' => 'Insufficient arguments in the POST'
        ]);
    }

    /**
     * @test
     */
    public function postCoinSell()
    {
        $response = $this->postJson(
            '/api/coin/sell',
            [
                "coin_id" => "2",
                "wallet_id" => "factory-wallet",
                "amount_usd" => 1
            ]
        );

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'status' => 'Successful operation',
            'message' => 'The sell has been successfully completed'
        ]);
    }
}
