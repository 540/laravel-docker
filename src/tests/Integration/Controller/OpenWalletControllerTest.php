<?php

namespace Tests\Feature\Wallet;

use App\Http\Controllers\OpenWalletController;
use App\Infraestructure\Database\WalletDatabase;
use App\Models\User;
use App\Services\OpenWalletService\OpenWalletService;
use App\Services\ServiceManager;
use Database\Factories\UserFactory;
use Database\Fakers\FakeUserManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tests\TestCase;
use Prophecy\Prophet;

class OpenWalletControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function getsHttpNotFoundWhenAInvalidUserIdIsReceived ()
    {
        $userId = 'invalidUserId';
//
//        $request = Request::create('/wallet/open', 'POST',[
//            'userId' => $userId
//        ]);

        $request = $this->call('POST', 'wallet/open', array(
            'user_id' => $userId
        ));

        $this->assertEquals(404, $request->getStatusCode());
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
        $userId = "1";
        $this->openWalletController = new OpenWalletController(new OpenWalletService(new WalletDatabase()));

        User::factory(User::class)->create();

        $request = Request::create('/wallet/open', 'POST',[
            'userId' => $userId
        ]);

        $response = $this->openWalletController->openWallet($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
