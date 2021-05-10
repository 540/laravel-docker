<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\Wallet;
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
        $wallet = Wallet::factory()->create()->first();

        $coin = Coin::factory()->create();
        $wallet->coins()->attach($coin, ['amount' => 1, 'value_usd' => 1]);

        $expectedJson = [];
        foreach ($wallet->coins as $coin){
            array_push($expectedJson, [
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->pivot->amount,
                'value_usd' => $coin->pivot->value_usd
            ]);
        }

        $response = $this->get('/api/wallet/' . $wallet->id);

        $response->assertStatus(Response::HTTP_OK)->assertJson($expectedJson);
    }
}
