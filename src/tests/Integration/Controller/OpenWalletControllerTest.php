<?php

namespace Tests\Integration\Controller;

use App\Http\Controllers\OpenWalletController;
use App\Infraestructure\Database\WalletDatabase;
use App\Models\User;
use App\Models\Wallet;
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

        $response = $this->postJson('api/wallet/open', [
            'user_id' => $userId
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function getsHttpBadRequestWhenUserIdFieldIsNotFound ()
    {
        $userId = 'invalidUserId';

        $response = $this->postJson('api/wallet/open', [
            'user' => $userId
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function getsSuccessfulOperationWhenUserIdIsFound ()
    {

        $user = User::factory(User::class)->create()->first();

        $response = $this->postJson('api/wallet/open', [
            'user_id' => $user->id
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
