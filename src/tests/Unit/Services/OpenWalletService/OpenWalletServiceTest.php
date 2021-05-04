<?php

namespace Tests\Services\OpenWalletService;

use App\Http\Controllers\OpenWalletController;
use App\Infraestructure\Database\DatabaseManager;
use App\Models\Wallet;
use App\Services\OpenWalletService\OpenWalletService;
use App\Services\ServiceManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class OpenWalletServiceTest extends TestCase
{
    private $databaseManager;
    private OpenWalletService $openWalletService;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet;
        $this->databaseManager = $prophet->prophesize(DatabaseManager::class);
        $this->openWalletService = new OpenWalletService($this->databaseManager->reveal());
    }

    /**
     * @test
     **/
    public function getsErrorWhenAUserDoesNotExist ()
    {
        $userId = "invalidUserId";
        $request = Request::create('/wallet/open', 'POST',[
            'userId' => $userId
        ]);

        $this->databaseManager->set("userId", $userId)->willReturn(null);

        $response = $this->openWalletService->getResponse($request);
        $expectedResult = "user not found";

        $this->assertEquals($expectedResult, $response);
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
        $wallet = new Wallet($userId, $walletId);
        $this->databaseManager->set("userId", $userId)->willReturn($wallet);

        $response = $this->openWalletService->getResponse($request);

        $this->assertEquals($walletId, $response);
    }

}
