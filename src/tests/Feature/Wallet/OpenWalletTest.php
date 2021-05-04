<?php

namespace Tests\Feature\Wallet;

use App\Http\Controllers\OpenWalletController;
use App\Infraestructure\Database\WalletDatabase;
use App\Models\User;
use App\Services\OpenWalletService\OpenWalletService;
use App\Services\ServiceManager;
use Database\Fakers\FakeUserManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tests\TestCase;
use Prophecy\Prophet;

class OpenWalletTest extends TestCase
{

    private OpenWalletController $openWalletController;

    /**
     * @test
     **/
    public function getsHttpNotFoundWhenAInvalidUserIdIsReceived ()
    {
        $userId = 'invalidUserId';
        $this->openWalletController = new OpenWalletController(new OpenWalletService(new WalletDatabase()));


        $request = Request::create('/wallet/open', 'POST',[
            'userId' => $userId
        ]);

        $response = $this->openWalletController->openWallet($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function getsHttpBadRequestWhenUserIdFieldIsNotFound ()
    {
        $userId = "unknow";

        $this->openWalletController = new OpenWalletController(new OpenWalletService(new WalletDatabase()));

        $request = Request::create('/wallet/open', 'POST',[
            'user' => $userId
        ]);

        $response = $this->openWalletController->openWallet($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenUserIdIsFound ()
    {
        $userId = "validUserId";
        $this->openWalletController = new OpenWalletController(new OpenWalletService(new WalletDatabase()));

        $fakeUserManager = new FakeUserManager(new User($userId));
        $fakeUserManager->insertFakeUser();

        $request = Request::create('/wallet/open', 'POST',[
            'userId' => $userId
        ]);

        $response = $this->openWalletController->openWallet($request);

        $fakeUserManager->deleteFakeUser();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
