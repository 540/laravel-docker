<?php

namespace Tests\Integration;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class BalanceControllerTest extends TestCase
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
    public function balanceWithSuccessResponse(){
        $response = $this->get('/api/1/balance');
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function notFoundWallet_BadRequestIsGiven(){
        $response = $this->get('/api/404/balance');
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->assertExactJson(["error" => "wallet not found"]);
    }
}
