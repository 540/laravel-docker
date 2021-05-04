<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\OpenWalletController;
use App\Services\OpenWalletService\OpenWalletService;
use App\Services\ServiceManager;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Prophecy\Prophet;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    private $openWalletService;
    private OpenWalletController $openWalletController;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet;
        $this->openWalletService = $prophet->prophesize(OpenWalletService::class);
        $this->openWalletController = new OpenWalletController($this->openWalletService->reveal());
    }

    /**
     * @test
     **/
    public function getsHttpNotFoundWhenAInvalidUserIdIsReceived ()
    {
        $userId = "invalidUserId";
        $request = Request::create('/wallet/open', 'POST',[
            'userId' => $userId
        ]);

        $this->openWalletService->execute($userId)->willThrow(new Exception("Error"));

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
        $userIdField = "invalidUserIdField";
        $request = Request::create('/wallet/open', 'POST',[
            $userIdField => 'userId'
        ]);

        $this->openWalletService->execute($userIdField)->willReturn("user id field not found");

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
        $userId = "validUserId";
        $request = Request::create('/wallet/open', 'POST',[
            'userId' => $userId
        ]);

        $walletId = "validWalletId";
        $this->openWalletService->execute($userId)->willReturn($walletId);

        $response = $this->openWalletController->openWallet($request);

        $expectedResponse = response()->json([
            'walletId' => $walletId
        ],Response::HTTP_OK);

        $this->assertEquals($expectedResponse, $response);
    }
}
