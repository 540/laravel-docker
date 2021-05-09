<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\SellCoinController;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Prophecy\Prophet;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
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
     **/
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
}
