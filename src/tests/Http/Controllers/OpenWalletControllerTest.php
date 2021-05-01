<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\OpenWalletController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    /**
     * @test
     **/
    public function getsHttpNotFoundWhenAInvalidUserIdIsReceived ()
    {
        $openWalletController = new OpenWalletController();

        $request = Request::create('/wallet/open', 'POST',[
            'userId' => 'wrong'
        ]);

        $response = $openWalletController->openWallet($request);

        $expectedResponse = response()->json([
            'error' => "Error while creating the wallet"
        ],Response::HTTP_NOT_FOUND);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     **/
    public function getsHttpBadRequestWhenUserIdFieldIsNotFound ()
    {
        $openWalletController = new OpenWalletController();

        $request = Request::create('/wallet/open', 'POST',[
            'user_Id' => 'wrong'
        ]);

        $response = $openWalletController->openWallet($request);

        $expectedResponse = response()->json([
            'error' => "Error while creating the wallet"
        ],Response::HTTP_BAD_REQUEST);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenUserIdIsFound ()
    {
        $openWalletController = new OpenWalletController();

        $request = Request::create('/wallet/open', 'POST',[
            'userId' => 'userId'
        ]);

        $response = $openWalletController->openWallet($request);

        $expectedResponse = response()->json([
            'walletId' => "walletTest"
        ],Response::HTTP_OK);

        $this->assertEquals($expectedResponse, $response);
    }
}
