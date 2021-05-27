<?php

namespace Tests\Integration;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SellCoinsControllerTest extends TestCase
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
    public function sellCoinWithSuccessResponse(){
        $response = $this->postJson('/api/coin/sell',['coin_id' => '90', 'wallet_id' => '1', 'amount_coins'=>1]);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function sellCoisNotEnoughAmountBoughtResponse(){
        $response = $this->postJson('/api/coin/sell',['coin_id' => '90', 'wallet_id' => '2', 'amount_coins'=>1]);
        $this->assertEquals("error:not enough coins",$response);
    }

    /**
     * @test
     */
    public function insertedWalletIdDoesNotExist_BadRequestIsGiven(){
        $response = $this->postJson('/api/coin/sell',['coin_id' => '90', 'wallet_id' => '1', 'amount_coins'=>1]);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->assertExactJson(["error" => "wallet not found"]);
    }

}
