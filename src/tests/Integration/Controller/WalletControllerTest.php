<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function noWalletFoundForGivenWalletId()
    {
        User::factory(User::class)->create();
        Wallet::factory(Wallet::class)->create();
        Coin::factory(Coin::class)->create();
        WalletCoin::factory(WalletCoin::class)->create();

        $response = $this->get('/api/wallet/error-wallet');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'status' => 'Wallet with the specified ID was not found',
            'message' => 'Wallet not found'
        ]);
    }

    /**
     * @test
     */
    public function userIsEarlyAdopter()
    {
        User::factory(User::class)->create();
        Wallet::factory(Wallet::class)->create();
        Coin::factory(Coin::class)->create();
        WalletCoin::factory(WalletCoin::class)->create();

        $response = $this->get('/api/wallet/factory-wallet');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            array(
                "coin_id" => "90",
                "name" => "Bitcoin",
                "symbol" => "BTC",
                "amount" => 0.01,
                "value_usd" => 553.5753
            )
        ]);
    }
}
