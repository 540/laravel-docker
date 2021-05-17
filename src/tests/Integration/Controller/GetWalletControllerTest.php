<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetWalletControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        User::factory(User::class)->create();
        Wallet::factory(Wallet::class)->create();
        Coin::factory(Coin::class)->create();
        WalletCoin::factory(WalletCoin::class)->create();
    }

    /**
     * @test
     */
    public function getWalletWalletNotFound()
    {
        $response = $this->get('/api/wallet/error-wallet');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'status' => 'Wallet with the specified ID was not found',
            'message' => 'Wallet not found'
        ]);
    }

    /**
     * @test
     */
    public function getWallet()
    {
        $response = $this->get('/api/wallet/factory-wallet');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                "amount" => "1000000.0",
                "coin_id" => "2",
                "name" => "Dogecoin",
                "symbol" => "DOGE",
            ]);
    }
}
