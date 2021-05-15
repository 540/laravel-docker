<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetWalletBalanceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function noCryptocurrenciesFoundGivenWrongWalletId()
    {
        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['error' => 'a wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function balanceIsGivenForASpecifiedWalletId()
    {
        $user = User::factory(User::class)->create();

        $wallet = Wallet::factory(Wallet::class)->make();

        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id);

        $coin = Coin::factory(Coin::class)->make();

        $wallet->coins()->save($coin);

        $response = $this->get('/api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_OK);
    }
}
