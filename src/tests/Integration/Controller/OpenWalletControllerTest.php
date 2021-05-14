<?php

namespace Tests\Integration\Controller;

use App\Errors\Errors;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function noWalletIsCreatedGivenAnExistentUserId ()
    {
        Wallet::factory()->create();

        $userId = 'existentUserId';
        $response = $this->postJson('api/wallet/open', [
            'user_id' => $userId
        ]);

        $response->assertStatus(Response::HTTP_NOT_ACCEPTABLE)->assertExactJson([Errors::ERROR_FIELD => Errors::WALLET_ALREADY_EXISTS_FOR_THIS_USER]);
    }

    /**
     * @test
     **/
    public function noWalletIsCreatedGivenABadRequest()
    {
        $userId = 'invalidUserId';
        $response = $this->postJson('api/wallet/open', [
            'user' => $userId
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([Errors::ERROR_FIELD => Errors::BAD_REQUEST]);
    }

    /**
     * @test
     **/
    public function walletIsCreatedGivenAValidUserId()
    {
        $userId = 'validUserId';
        $response = $this->postJson('api/wallet/open', [
            'user_id' => $userId
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['wallet_id' => '1']);
    }
}
