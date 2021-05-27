<?php

namespace Tests\Integration;


use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetWalletCryptocurrenciesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void{
        parent::setUp();
        Wallet::factory(Wallet::class)->create();
        Transaction::factory(Transaction::class)->create();
    }

    /**
     * @test
     */
    public function getCryptocurrenciesWithSuccessResponse(){
        $response = $this->get('/api/wallet/1');
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function notFoundWallet_BadRequestIsGiven(){
        $response = $this->get('/api/wallet/404');
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->assertExactJson(["error" => "wallet not found"]);
    }
}
