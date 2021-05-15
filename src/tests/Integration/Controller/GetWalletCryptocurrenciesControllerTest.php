<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\User;
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
    public function noCryptocurrenciesFoundGivenAWrongWalletId()
    {
        $response = $this->get('api/wallet/2');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['Error' => 'A wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function noCryptocurrenciesFoundGivenAValidWalletId()
    {
        $user = User::factory()->create()->first();
        $wallet = Wallet::factory()->make();
        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id)->first();

        $expectedJson = [];

        $response = $this->get('/api/wallet/' . $wallet->id);

        $response->assertStatus(Response::HTTP_OK)->assertJson($expectedJson);
    }

    /**
     * @test
     */
    public function aCryptocurrencyIsFoundGivenAValidWalletId()
    {
        $user = User::factory()->create()->first();
        $wallet = Wallet::factory()->make();
        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id)->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);

        $expectedJson = [
            [
                'coin_id' => $coin->coin_id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]
        ];

        $response = $this->get('/api/wallet/' . $wallet->id);

        $response->assertStatus(Response::HTTP_OK)->assertJson($expectedJson);
    }

    /**
     * @test
     */
    public function multipleCryptocurrenciesAreFoundGivenAValidWalletId()
    {
        $user = User::factory()->create()->first();
        $wallet = Wallet::factory()->make();
        $user->wallet()->save($wallet);

        $wallet = Wallet::query()->find($user->wallet->id)->first();
        $coins = Coin::factory(Coin::class)->count(2)->make();
        $wallet->coins()->saveMany($coins);

        $expectedJson = [];
        foreach ($wallet->coins as $coin)
        {
            array_push($expectedJson, [
                'coin_id' => $coin->coin_id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]);
        }

        $response = $this->get('/api/wallet/' . $wallet->id);

        $response->assertStatus(Response::HTTP_OK)->assertJson($expectedJson);
    }
}
