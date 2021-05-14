<?php

namespace Tests\Integration;

use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class BuyCoinControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var WalletDataSource
     */
    private $dataSource;

    /**
     * @test
     */
    public function insertedWalletIdDoesNotExist_BadRequestIsGiven(){
        Wallet::factory(Wallet::class)->create();

        $response = $this->postJson("/api/coin/buy",["coind_id" => '90', 'wallet_id' => '20', 'amount_usd' => 500000]);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->assertExactJson(["error" => "wallet not found"]);
    }
}
