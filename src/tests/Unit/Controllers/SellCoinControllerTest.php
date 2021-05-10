<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\SellCoinController;
use App\Models\Coin;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Prophecy\Prophet;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    /* public function noCoinFoundForGivenId()
    {
        Coin::factory(Coin::class)->create();

        $response = $this->post('/coin/sell');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Coin not found']);
    } */

    private $sellCoinService;
    private SellCoinController $sellCoinController;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet;
        $this->sellCoinService = $prophet->prophesize(SellCoinService::class);
        $this->sellCoinController = new SellCoinController($this->sellCoinService->reveal());
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsHttpNotFoundWhenInvalidCoinIdIsReceived()
    {
        $coinId = "invalidCoinId";
        $walletId = "validWalletId";
        $amountUSD = 0;
        $request = Request::create('/coin/sell', 'POST', [
            'coinId' => $coinId,
            'walletId' => $walletId,
            'amountUSD' => $amountUSD
        ]);

        $this->sellCoinService->execute($coinId, $walletId, $amountUSD)
            ->willThrow(new Exception("Error"));

        $response = $this->sellCoinController->sellCoin($request);
        $expectedResponse = response()->json([
            'error' => "Error while selling coins"
        ], Response::HTTP_NOT_FOUND);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsHttpBadRequestWhenCoinIdFieldIsNotFound()
    {
        $coinIdField = "invalidCoinIdField";
        $walletId = "validWalletId";
        $amountUSD = 0;
        $request = Request::create('/coin/sell', 'POST', [
            $coinIdField => 'coinId',
            'walletId' => $walletId,
            'amountUSD' => $amountUSD
        ]);

        $this->sellCoinService->execute($coinIdField, $walletId, $amountUSD)
            ->willThrow(new Exception("Error"));

        $response = $this->sellCoinController->sellCoin($request);

        $expectedResponse = response()->json([
            'error' => "Error while selling coins"
        ], Response::HTTP_BAD_REQUEST);

        $this->assertEquals($expectedResponse, $response);
    }
}
