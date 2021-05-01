<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\OpenWalletController;
use App\Services\ServiceManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Prophecy\Prophet;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    private ServiceManager $serviceManager;
    private Prophet $prophet;
    private OpenWalletController $openWalletController;

    protected function setUp():void
    {
        $this->prophet = new Prophet;
        $this->serviceManager = $this->prophet->prophesize(ServiceManager::class);
        $this->openWalletController = new OpenWalletController($this->serviceManager->reveal());
    }

    /**
     * @test
     **/
    public function getsHttpNotFoundWhenAInvalidUserIdIsReceived ()
    {
        $this->setUp();

        $request = Request::create('/wallet/open', 'POST',[
            'userId' => 'wrong'
        ]);

        $this->serviceManager->getResponse($request)->willReturn("wrong");

        $response = $this->openWalletController->openWallet($request);

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
        $this->setUp();

        $request = Request::create('/wallet/open', 'POST',[
            'user_Id' => 'wrong'
        ]);

        $this->serviceManager->getResponse($request)->willReturn(null);

        $response = $this->openWalletController->openWallet($request);

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
        $this->setUp();

        $request = Request::create('/wallet/open', 'POST',[
            'userId' => 'userId'
        ]);

        $this->serviceManager->getResponse($request)->willReturn("walletTest");

        $response = $this->openWalletController->openWallet($request);

        $expectedResponse = response()->json([
            'walletId' => "walletTest"
        ],Response::HTTP_OK);

        $this->assertEquals($expectedResponse, $response);
    }
}
