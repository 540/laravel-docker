<?php

namespace Tests\app\Application\EarlyAdopter;

use App\Application\CoinDataSource\BuyCoinDataSource;
use App\Application\CoinService\BuyCoinService;
use App\Domain\Coin;
use Exception;
use Illuminate\Http\Response;
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
        $this->coinDataSource
            ->expects('findByCoinId')
            ->with('10','1',0)
            ->once()
            ->andReturn("successful operation");

        $response = $this->coinService->execute('10','1',0);


        $this->assertEquals("successful operation",$response);
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
    /**
     * @test
     */
    public function validIdReturn()
    {
        $this->coinDataSource
            ->expects('findByCoinId')
            ->with('10','1',0)
            ->once()
            ->andReturn("successful operation");

        $response = $this->coinService->SellCoin('10','1',0);


        $this->assertEquals("successful operation",$response);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $id = '2000';

        $this->coinDataSource
            ->expects('findByCoinId')
            ->with($id,'1',0)
            ->once()
            ->andThrow(new Exception("A coin with the specified ID was not found."));

        $this->expectExceptionMessage("A coin with the specified ID was not found");

        $this->coinService->SellCoin($id,'1',0);
    }
}

