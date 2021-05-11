<?php

namespace Tests\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinRepository;
use App\Infraestructure\Database\DatabaseManager;
use App\Models\Coin;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
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
    public function noCoinIdFound()
    {
        $coinId = "invalidCoinId";
        $eloquentCoinRepository = $this->prophet->prophesize(EloquentCoinRepository::class);
        $eloquentCoinRepository->findCoinById($coinId)->willThrow(Exception::class);
        $sellCoinService = new SellCoinService($eloquentCoinRepository->reveal());

        $this->expectException(Exception::class);

        $sellCoinService->execute($coinId);
    }

    /**
     * @test
     * @throws Exception
     */
    public function sellsCoinSuccessfullyWhenCoinIdIsCorrect()
    {
        $coin = Coin::factory()->create()->first();
        $expectedCoin = [];
        array_push($expectedCoin, [
            'id' => $coin->id,
            'wallet_id' => $coin->wallet_id,
            'coin_id' => $coin->coind_id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'amount' => $coin->amount,
            'value_usd' => $coin->value_usd
        ]);

        $eloquentCoinRepository = $this->prophet->prophesize(EloquentCoinRepository::class);
        $eloquentCoinRepository->findCoinById($coin->coinId)->shouldBeCalledOnce()->willReturn($coin);
        $sellCoinService = new SellCoinService($eloquentCoinRepository->reveal());
        $returnedCoin = $sellCoinService->execute($coin->coinId);

        $this->assertEquals($expectedCoin, $returnedCoin);
    }
}
