<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\DataSource\Database\EloquentCoinBuyerDataSource;
use App\Http\Controllers\coinBuyerController;
use App\Models\User;
use App\Models\Wallet;
use App\Services\CoinBuy\coinBuyerService;

use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

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

        $request = Request::create('/wallet/buy', 'POST',[
            'coin' => 1,
            'wallet_id' => 0,
            'amount_usd' => 50
        ]);

        $response = $this->get($request);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson(['error' => 'wrong field']);

    }

    /**
     * @test
     **/
    public function getsHttpNotFoundWhenWalletWasNotFound ()
    {
        $request = Request::create('/wallet/buy', 'POST',[
            'coin_id' => 1,
            'wallet_id' => 0,
            'amount_usd' => 50
        ]);

        $response = $this->get($request);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['error' => 'wrong field']);
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletAndCoinAreFound ()
    {

        $wallet = Wallet::factory()->make();
        $coins = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coins);

        $request = Request::create('/wallet/buy', 'POST',[
            'coin_id' => 1,
            'wallet_id' => 1,
            'amount_usd' => 50
        ]);

        $response = $this->get($request);

        $response->assertStatus(Response::HTTP_OK)->assertJson(['bought' => 'success operation']);
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletIsFoundButNotCoin ()
    {
        //Se instancia wallet pero no coin
        $wallet = Wallet::factory()->make();

        //Como hacer el get para hacer un getteo de un walletId Existente

        $request = Request::create('/wallet/buy', 'POST',[
            'coin_id' => 90,
            'wallet_id' => $wallet->get('id'),
            'amount_usd' => 50
        ]);

        $response = $this->get($request);

        $response->assertStatus(Response::HTTP_OK)->assertJson(['bought' => 'success operation']);
    }






}
