<?php

namespace Tests\Integration\Controller;

use App\DataSource\API\CoinDataSource;
use App\DataSource\API\FakeCoinDataSource;
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
        $this->app->bind(CoinDataSource::class, FakeCoinDataSource::class);

        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['error' => 'a wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function balanceIsGivenForASpecifiedWalletId()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinDataSource::class);

        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $coin = Coin::factory(Coin::class)->make();

        $wallet->coins()->save($coin);

        $response = $this->get('/api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_OK);
    }
}
