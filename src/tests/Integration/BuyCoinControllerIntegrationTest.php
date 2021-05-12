<?php

namespace Tests\Integration;

use App\Http\Services\Adopter\BuyCoinsAdapterService;
use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class BuyCoinControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var WalletDataSource
     */
    private $dataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->apiDataSource = $prophet->prophesize(ApiSource::class);
        $this->dataSource = new WalletDataSource();
        $this->buyCoinsService = new BuyCoinsAdapterService($this->dataSource, $this->apiDataSource->reveal());
    }

    /**
     * @test
     */
    public function insertedWalletIdDoesNotExist_BadRequestIsGiven(){
        Wallet::factory(Wallet::class)->create();

        $response = $this->get("/api/coin/buy",["coind_id" => '90', 'wallet_id' => '20', 'amount_usd' => 500000]);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->assertExactJson(["error" => "wallet not found"]);
    }
}
