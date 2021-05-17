<?php

namespace Tests\Integration\Controller;

use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetWalletBalanceControllerTest extends TestCase
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
    public function getWalletBalanceWalletNotFound()
    {
        $response = $this->get('/api/wallet/error-wallet/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson([
            'status' => 'Wallet with the specified ID was not found',
            'message' => 'Wallet not found'
        ]);
    }

    /**
     * @test
     */
    public function getWalletBalance()
    {
        $response = $this->get('/api/wallet/factory-wallet/balance');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "balance_usd"
            ]);
    }
}
