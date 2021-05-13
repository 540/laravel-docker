<?php

namespace Tests\Integration\Controller;

use App\DataSource\API\CoinDataSource;
use App\DataSource\API\FakeCoinDataSource;
use App\Errors\Errors;
use App\Models\Coin;
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
    public function walletIsNotFoundGivenInvalidWalletId()
    {
        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson([Errors::ERROR_FIELD => Errors::WALLET_NOT_FOUND]);
    }

    /**
     * @test
     */
    public function CoinsNotFoundInAPIGivenWrongCoinID()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinDataSource::class);

        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $coin = Coin::factory(Coin::class)->make();

        $coin->coin_id = 'invalidCoinId';

        $wallet->coins()->save($coin);

        $response = $this->get('/api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson([Errors::ERROR_FIELD => Errors::WRONG_COIN_ID]);
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

        $response->assertStatus(Response::HTTP_OK)->assertJson(['balance_usd' => 49]);
    }
}
