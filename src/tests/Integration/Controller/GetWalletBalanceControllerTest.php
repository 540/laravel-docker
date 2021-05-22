<?php

namespace Tests\Integration\Controller;

use App\DataSource\API\CoinDataSource;
use App\Errors\Errors;
use App\Models\Coin;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Doubles\FakeNegativeBalanceCoinDataSource;
use Tests\Doubles\FakePositiveBalanceCoinDataSource;
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

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson([Response::HTTP_NOT_FOUND => Errors::WALLET_NOT_FOUND]);
    }

    /**
     * @test
     */
    public function coinsNotFoundInAPIGivenWrongCoinID()
    {
        $this->app->bind(CoinDataSource::class, FakePositiveBalanceCoinDataSource::class);

        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();

        $coin->coin_id = 'invalidCoinId';
        $wallet->coins()->save($coin);

        $response = $this->get('/api/wallet/' . $wallet->id . '/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson([Response::HTTP_NOT_FOUND => Errors::WRONG_COIN_ID]);
    }

    /**
     * @test
     */
    public function positiveBalanceIsObtainedGivenAValidWalletId()
    {
        $this->app->bind(CoinDataSource::class, FakePositiveBalanceCoinDataSource::class);

        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);

        $response = $this->get('/api/wallet/' . $wallet->id . '/balance');

        $response->assertStatus(Response::HTTP_OK)->assertJson(['balance_usd' => 49]);
    }

    /**
     * @test
     */
    public function negativeBalanceIsObtainedGivenAValidWalletId()
    {
        $this->app->bind(CoinDataSource::class, FakeNegativeBalanceCoinDataSource::class);

        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);

        $response = $this->get('/api/wallet/' . $wallet->id . '/balance');

        $response->assertStatus(Response::HTTP_OK)->assertJson(['balance_usd' => -1]);
    }
}
