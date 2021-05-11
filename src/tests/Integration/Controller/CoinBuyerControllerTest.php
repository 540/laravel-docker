<?php

namespace Tests\Integration\Controller;

use App\DataSource\Database\CoinBuyerDataSource;
use App\Http\Controllers\coinBuyerController;
use App\Models\User;
use App\Models\Wallet;
use App\Services\CoinBuy\coinBuyerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class CoinBuyerControllerTest extends TestCase
{
    private $coinBuyerController;

    /**
     * @test
     * Falta cambiarlos por las peticiones json
     **/
    public function getsHttpNotFoundWhenAInvalidWalletIdIsReceived ()
    {
        $this->coinBuyerController = new CoinBuyerController(new coinBuyerService(new CoinBuyerDataSource()));

        Wallet::factory(Wallet::class)->create();

        $request = Request::create('/wallet/buy', 'POST',[
            'coin_id' => 1,
            'wallet_id' => 0,
            'amount_usd' => 50
        ]);

        $response = $this->coinBuyerController->buyCoin($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function getsHttpBadRequestWhenAnInvalidFieldIsReceived ()
    {
        $this->coinBuyerController = new CoinBuyerController(new coinBuyerService(new CoinBuyerDataSource()));

        Wallet::factory(Wallet::class)->create();

        $request = Request::create('/wallet/buy', 'POST',[
            'coin_id' => 1,
            'wallet_id' => 0,
            'amount_usd' => 50
        ]);

        $response = $this->coinBuyerController->buyCoin($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletAndCoinAreFound ()
    {
        $this->coinBuyerController = new CoinBuyerController(new coinBuyerService(new CoinBuyerDataSource()));

        Wallet::factory(Wallet::class)->create();
        Coin::factory(Wallet::class)->create();

        $request = Request::create('/wallet/buy', 'POST',[
            'coin_id' => 1,
            'wallet_id' => 1,
            'amount_usd' => 50
        ]);

        $response = $this->coinBuyerController->buyCoin($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenWalletIsFoundButNotCoin ()
    {
        $this->coinBuyerController = new CoinBuyerController(new coinBuyerService(new CoinBuyerDataSource()));

        Wallet::factory(Wallet::class)->create();

        $request = Request::create('/wallet/buy', 'POST',[
            'coin_id' => 1,
            'wallet_id' => 1,
            'amount_usd' => 50
        ]);

        $response = $this->coinBuyerController->buyCoin($request);

        $this->assertEquals(200, $response->getStatusCode());
    }






}
