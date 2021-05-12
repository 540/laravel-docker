<?php

namespace Tests\Integration\Controller;

use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function noWalletIsCreatedWhenExistingUserIdIsReceived ()
    {
        Wallet::factory()->create();

        $userId = 'existingUserId';

        $response = $this->postJson('api/wallet/open', [
            'user_id' => $userId
        ]);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     **/
    public function noWalletIsCreatedWhenBadRequestIsReceived ()
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

        $userId = 'validUserId';

        $response = $this->postJson('api/wallet/open', [
            'user_id' => $userId
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
