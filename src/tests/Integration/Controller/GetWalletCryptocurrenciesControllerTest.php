<?php

namespace Tests\Integration\Controller;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetWalletCryptocurrenciesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function noCryptocurrenciesFoundGivenWrongWalletId()
    {
        Wallet::factory(Wallet::class)->create();

        $response = $this->get('api/wallet/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['error' => 'a wallet with the specified ID was not found.']);
    }

//    /**
//     * @test
//     */
//    public function userIsEarlyAdopter()
//    {
//        User::factory(User::class)->create();
//
//        $response = $this->get('/api/user/email@email.com');
//
//        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['earlyAdopter' => true]);
//    }
}
