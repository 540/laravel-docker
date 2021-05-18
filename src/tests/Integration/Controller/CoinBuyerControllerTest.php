<?php

namespace Tests\Integration\Controller;

use App\Errors\Errors;
use App\Models\Coin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Http\Response;
use Tests\TestCase;

class CoinBuyerControllerTest extends TestCase
{

    use RefreshDatabase;
    private $coinBuyerController;

    /**
     * @test
     * Falta cambiarlos por las peticiones json
     **/
    public function getsHttpBadRequestWhenAInvalidRequestFieldIsReceived ()
    {

        $response = $this->postJson('api/coin/buy', [
            'coin' => '1',
            'wallet_id' => 0,
            'amount_usd' => 50
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson(['error' => Errors::BAD_REQUEST_ERROR]);

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

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['error' =>  Errors::COIN_SPICIFIED_ID_NOT_FOUND]);
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletAndCoinAreFound ()
    {

        $wallet = Wallet::factory()->create()->first();
        $coins = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coins);
        $coins = Coin::query()->where('coin_id',$coins->coin_id)->first();

        $response = $this->postJson('api/coin/buy', [
            'coin_id' => $coins->coin_id,
            'wallet_id' =>  $wallet->id,
            'amount_usd' => 50
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJson(['bought' => 'successful operation']);
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletIsFoundButNotCoin ()
    {
        //Se instancia wallet pero no coin
        $wallet = Wallet::factory()->create()->first();

        $response = $this->postJson('api/coin/buy', [
            'coin_id' => '1',
            'wallet_id' =>  $wallet->id,
            'amount_usd' => 50
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJson(['bought' => 'successful operation']);
    }


}
