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

        $response = $this->get('api/wallet/2');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['error' => 'a wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function cryptocurrenciesAreGivenForASpecifiedWalletId()
    {
        $coin = Coin::factory(Coin::class)->create()->first();
        $walletCoin = WalletCoin::factory(WalletCoin::class)->create()->first();

        $expectedJson = [
            'coin_id' => $walletCoin->coin_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $walletCoin->amount,
            'value_usd' => $walletCoin->value_usd
        ];

        $response = $this->get('/api/wallet/1');

        $response->assertStatus(Response::HTTP_OK)->assertJson($expectedJson);
    }
}
