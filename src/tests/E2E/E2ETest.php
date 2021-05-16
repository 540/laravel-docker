<?php

namespace Tests\E2E;

use App\Models\Coin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class E2ETest extends TestCase
{
    use RefreshDatabase;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        User::factory(User::class)->create();
        Coin::factory(Coin::class)->create();
    }

    /**
     * @test
     */
    public function normalUserWork()
    {
        $this->get('/api/status')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
            'status' => 'Success',
            'message' => 'Systems are up and running'
        ]);

        $newWallet = $this->postJson('/api/wallet/open', ['user_id' => 'factory-user']);

        $newWallet->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "wallet_id"
            ]);

        $walletId = $newWallet->baseResponse->original['wallet_id'];

        $this->postJson(
            '/api/coin/buy',
            [
                "coin_id" => "2",
                "wallet_id" => $walletId,
                "amount_usd" => 50
            ]
        )->assertStatus(Response::HTTP_OK)->assertExactJson([
            'status' => 'Successful operation',
            'message' => 'The buy has been successfully completed'
        ]);

        $this->get('/api/wallet/'.$walletId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                "coin_id" => "2",
                "name" => "Dogecoin",
                "symbol" => "DOGE",
            ]);

        $this->get('/api/wallet/'.$walletId.'/balance')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "balance_usd"
            ]);

        $this->postJson(
            '/api/coin/sell',
            [
                "coin_id" => "2",
                "wallet_id" => $walletId,
                "amount_usd" => 25
            ]
        )->assertStatus(Response::HTTP_OK)->assertExactJson([
            'status' => 'Successful operation',
            'message' => 'The sell has been successfully completed'
        ]);

        $this->get('/api/wallet/'.$walletId)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                "coin_id" => "2",
                "name" => "Dogecoin",
                "symbol" => "DOGE",
            ]);

        $this->get('/api/wallet/'.$walletId.'/balance')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "balance_usd"
            ]);
    }
}
