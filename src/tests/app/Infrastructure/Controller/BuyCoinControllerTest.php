<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\BuyCoinDataSource;
use App\Domain\Coin;
use Illuminate\Http\Response;
use Mockery;
use Exception;
use Tests\TestCase;
define("token", array(
    'Content-Type: application/json'
));
class BuyCoinControllerTest extends TestCase
{
    private BuycoinDataSource $BuyCoinDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->BuyCoinDataSource = Mockery::mock(BuycoinDataSource::class);
        $this->app->bind(BuycoinDataSource::class, fn() => $this->BuyCoinDataSource);
    }

    /**
     * @test
     */
    public function coinWithGivenIdDoesNotExist()
    {
        $id = '2000';
        $wallet_id = "1";
        $amount_usd = 1;
        $this->BuyCoinDataSource
            ->expects('findByCoinId')
            ->with($id,$wallet_id,$amount_usd)
            ->once()
            ->andThrow(new Exception('A coin with the specified ID was not found.'));

        $fields = array("coin_id" => $id, "wallet_id" => $wallet_id, 'amount_usd' =>$amount_usd );

        $response = $this->post('api/coin/buy',$fields,token);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with the specified ID was not found.']);
    }
    /**
     * @test
     */
    public function errorInServer()
    {
        $id = '200';
        $wallet_id = "1";
        $amount_usd = 1;
        $this->BuyCoinDataSource
            ->expects('findByCoinId')
            ->with($id,$wallet_id,$amount_usd)
            ->once()
            ->andThrow(new Exception('Service Unavailible'));

        $fields = array("coin_id" => $id, "wallet_id" => $wallet_id, 'amount_usd' =>$amount_usd );

        $response = $this->post('api/coin/buy',$fields,token);

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service Unavailible']);
    }

    /**
     * @test
     */
    public function coinWithValidIdReturnJsonCoin()
    {
        $id = '10';
        $wallet_id = "1";
        $amount_usd = 1;
        $coin = new Coin(1,"10","BlackCoin","blackcoin",1,"BLK",1);
        $this->BuyCoinDataSource
            ->expects('findByCoinId')
            ->with($id,$wallet_id,$amount_usd)
            ->once()
            ->andReturn($coin);

        $fields = array("coin_id" => $id, "wallet_id" => $wallet_id, 'amount_usd' =>$amount_usd );

        $response = $this->post('api/coin/buy',$fields,token);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([$coin]);
    }
    /**
     * @test
     */
    public function errorCoinId()
    {
        $wallet_id = "1";
        $amount_usd = 1;


        $fields = array( "wallet_id" => $wallet_id, 'amount_usd' =>$amount_usd );

        $response = $this->post('api/coin/buy',$fields,token);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'coin_id mandatory']);
    }
    /**
     * @test
     */
    public function errorWalletId()
    {

        $fields = array( "coin_id"=>'1', 'amount_usd' =>1 );

        $response = $this->post('api/coin/buy',$fields,token);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'wallet_id mandatory']);
    }
    /**
     * @test
     */
    public function erroramount()
    {

        $fields = array( "coin_id"=>'1',"wallet_id"=>"1");

        $response = $this->post('api/coin/buy',$fields,token);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'amount_usd mandatory']);
    }
}

