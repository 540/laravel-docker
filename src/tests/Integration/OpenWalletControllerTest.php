<?php

namespace Tests\Integration;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class OpenWalletControllerTest extends TestCase
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
        $response = $this->postJson("/api/wallet/open",["user_id" => '1']);
        $response->assertStatus(Response::HTTP_OK);
    }


}
