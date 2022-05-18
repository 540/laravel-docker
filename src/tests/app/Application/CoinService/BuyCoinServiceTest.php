<?php

namespace Tests\app\Application\EarlyAdopter;

use App\Application\CoinDataSource\BuyCoinDataSource;
use App\Application\CoinService\BuyCoinService;
use App\Domain\Coin;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\CoinTest;

class BuyCoinServiceTest extends TestCase
{
    private BuyCoinService $coinService;
    private BuyCoinDataSource $coinDataSource;


    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinDataSource = Mockery::mock(BuyCoinDataSource::class);

        $this->coinService = new BuyCoinService($this->coinDataSource);
    }

    /**
     * @test
     */
    public function validIdReturnACoin()
    {
        $coin = new Coin(0,"10","BlackCoin","blackcoin",1,"BLK",1);

        $this->coinDataSource
            ->expects('findByCoinId')
            ->with('10','1',0)
            ->once()
            ->andReturn($coin);

        $response = $this->coinService->execute('10','1',0);

        $c1 = new CoinTest("10","BlackCoin","BLK",0,"blackcoin");
        $c2 = new CoinTest($response->getCoinId(),$response->getName(),$response->getSymbol(),0,$response->getNameId());

        $this->assertEquals($c1,$c2);
    }

    /**
     * @test
     */
    public function coinNotFoundReturnError()
    {
        $id = '2000';

        $this->coinDataSource
            ->expects('findByCoinId')
            ->with($id,'1',0)
            ->once()
            ->andThrow(new Exception("A coin with the specified ID was not found."));

        $this->expectExceptionMessage("A coin with the specified ID was not found");

        $this->coinService->execute($id,'1',0);
    }
}

