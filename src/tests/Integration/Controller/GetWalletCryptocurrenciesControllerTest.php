<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetWalletCryptocurrenciesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function noCryptocurrenciesFoundGivenWrongWalletId()
    {
        Wallet::factory(Wallet::class)->create();

        $response = $this->get('api/wallet/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['error' => 'a wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function cryptocurrenciesAreGivenForASpecifiedWalletId()
    {
        Wallet::factory(Wallet::class)->create();
        Coin::factory()->create();
        $walletCoin = WalletCoin::factory()->create();

        $response = $this->get('api/wallet/1');

        $response->assertStatus(Response::HTTP_OK)->assertJson($walletCoin);
    }


//    /**
//     * @test
//     */
//    public function userIsEarlyAdopter()
//    {
//        User::factory(User::class)->create();
//
//        $response = $this->get('/api/user/email@email.com');
//
//        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['earlyAdopter' => true]);
//    }
}
