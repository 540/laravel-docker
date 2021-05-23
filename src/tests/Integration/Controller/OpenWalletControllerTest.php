<?php

namespace Tests\Integration\Controller;

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

        $response->assertStatus(Response::HTTP_NOT_ACCEPTABLE)->assertExactJson([Response::HTTP_NOT_ACCEPTABLE => 'User with the specified ID already has a wallet.']);
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

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([Response::HTTP_BAD_REQUEST => 'Request fields have some errors.']);
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
