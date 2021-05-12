<?php

namespace Tests\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinRepository;
use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Prophecy\Prophet;

class SellCoinServiceTest extends TestCase
{
    use RefreshDatabase;

    private Prophet $prophet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->prophet = new Prophet;
    }

    /**
     * @test
     */
    public function coinIsNotFoundIfCoinIdIsNotCorrect()
    {
        $coinId = "invalidCoinId";
        $walletId = "validWalletId";
        $amountUSD = 0;
        $eloquentCoinRepository = $this->prophet->prophesize(EloquentCoinRepository::class);
        $eloquentCoinRepository->findCoinById($coinId)->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinRepository->reveal());

        $this->expectException(Exception::class);

        $sellCoinService->execute($coinId, $walletId, $amountUSD);
    }

    /**
     * @test
     * @throws Exception
     */
    public function coinIsFoundIfCoinIdIsCorrect()
    {
        $user = User::factory()->create()->first();
        $wallet = Wallet::factory()->create()->first();
        $coin = Coin::factory()->create()->first();
        $expectedCoin = [];
        array_push($expectedCoin, [
            'wallet_id' => $coin->wallet_id,
            'coin_id' => $coin->coin_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $coin->amount,
            'value_usd' => $coin->value_usd
        ]);

        $eloquentCoinRepository = $this->prophet->prophesize(EloquentCoinRepository::class);
        $eloquentCoinRepository->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willReturn($coin);
        $sellCoinService = new SellCoinService($eloquentCoinRepository->reveal());
        $returnedCoin = $sellCoinService->execute($coin->coin_id, $coin->wallet_id, 0);

        $this->assertEquals($coin->coin_id, $returnedCoin->coin_id);
    }

    /**
     * @test
     * @throws Exception
     */
    public function sellsCoinIfCoinIsFound()
    {
        // previous test + amountUSD logic
        //asserts that coin is sold
    }
}
