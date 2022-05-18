<?php

namespace Tests\app\Application\EarlyAdopter;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\CoinService\CoinService;
use App\Domain\Coin;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\CoinTest;

class CoinServiceTest extends TestCase
{
    private CoinService $coinService;
    private CoinDataSource $coinDataSource;


    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinDataSource = Mockery::mock(CoinDataSource::class);

        $this->coinService = new CoinService($this->coinDataSource);
    }

    /**
     * @test
     */
    public function validIdReturnACoin()
    {
        $coin = new Coin(0,"10","BlackCoin","blackcoin",1,"BLK",1);

        $this->coinDataSource
            ->expects('findByCoinId')
            ->with('10')
            ->once()
            ->andReturn($coin);

        $response = $this->coinService->execute('10');

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
            ->with($id)
            ->once()
            ->andThrow(new Exception("A coin with the specified ID was not found."));

        $this->expectException(Exception::class);

        $this->coinService->execute($id);
    }
}
