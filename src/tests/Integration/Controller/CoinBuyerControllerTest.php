<?php

namespace Tests\Integration\Controller;

use App\DataSource\API\CoinDataSource;
use App\Errors\Errors;
use App\Models\Coin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Wallet;
use Illuminate\Http\Response;
use Tests\Doubles\FakeCoinLoreDataSource;
use Tests\TestCase;

class CoinBuyerControllerTest extends TestCase
{
    use RefreshDatabase;
    private $coinBuyerController;

    /**
     * @test
     **/
    public function getsHttpBadRequestWhenAInvalidRequestFieldIsReceived ()
    {
        $response = $this->postJson('api/coin/buy', [
            'coin' => '1',
            'wallet_id' => 0,
            'amount_usd' => 50
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([Response::HTTP_BAD_REQUEST => Errors::BAD_REQUEST_ERROR]);

    }

    /**
     * @test
     **/
    public function getsHttpNotFoundWhenWalletWasNotFound ()
    {

        $response = $this->postJson('api/coin/buy', [
            'coin_id' => '1',
            'wallet_id' => 0,
            'amount_usd' => 50
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson([Response::HTTP_NOT_FOUND =>  Errors::WALLET_NOT_FOUND]);
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletAndCoinAreFound ()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSource::class);

        $wallet = Wallet::factory()->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('coin_id',$coin->coin_id)->first();

        $response = $this->postJson('api/coin/buy', [
            'coin_id' => $coin->coin_id,
            'wallet_id' =>  $wallet->id,
            'amount_usd' => 50
        ]);

        $updatedCoin = Coin::query()
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $coin->wallet_id)
            ->first();

        $response->assertStatus(Response::HTTP_OK)->assertJson([Response::HTTP_OK => 'successful operation']);

        $this->assertEquals($coin->amount+50, $updatedCoin->amount);
        $this->assertEquals($coin->value_usd+50, $updatedCoin->value_usd);
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletIsFoundButNotCoin ()
    {

        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSource::class);

        $wallet = Wallet::factory()->create()->first();

        $response = $this->postJson('api/coin/buy', [
            'coin_id' => '1',
            'wallet_id' =>  $wallet->id,
            'amount_usd' => 50
        ]);

        $createdCoin = Coin::query()
            ->where('coin_id', 1)
            ->where('wallet_id', $wallet->id)
            ->first();

        $response->assertStatus(Response::HTTP_OK)->assertJson([Response::HTTP_OK => 'successful operation']);

        $this->assertEquals(1, $createdCoin->id);
        $this->assertEquals($wallet->id, $createdCoin->wallet_id);

    }
}
