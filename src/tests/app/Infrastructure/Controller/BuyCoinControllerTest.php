<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\BuyCoinDataSource;
use App\Domain\Coin;
use Mockery;
use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;
use Illuminate\Http\Response;

class BuyCoinControllerTest extends TestCase
{
    private BuycoinDataSource $coinDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->coinDataSource = Mockery::mock(BuycoinDataSource::class);
        $this->app->bind(BuycoinDataSource::class, fn() => $this->coinDataSource);
    }

    /**
     * @test
     */
    public function coinWithGivenIdDoesNotExist()
    {
        $id = '2000';
        $this->coinDataSource
            ->expects('findByCoinId')
            ->with($id)
            ->once()
            ->andThrow(new Exception('A coin with the specified ID was not found.'));

        $response = $this->get('/api/coin/status/2000');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with the specified ID was not found.']);
    }


    /**
     * @test
     */
    public function errorInServer()
    {
        $id = '200';
        $this->coinDataSource
            ->expects('findByCoinId')
            ->with($id)
            ->once()
            ->andThrow(new Exception('Service Unavailible'));

        $response = $this->get('/api/coin/status/200');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service Unavailible']);
    }

    /**
     * @test
     */
    public function coinWithValidIdReturnJsonCoin()
    {
        $id = '10';
        $coin = new Coin(0,"10","BlackCoin","blackcoin",1,"BLK",1);

        $this->coinDataSource
            ->expects('findByCoinId')
            ->with($id)
            ->once()
            ->andReturn($coin);

        $response = $this->get('/api/coin/status/10');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([$coin]);
    }
}

