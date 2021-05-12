<?php

namespace Tests\Integration\DataSources\API;

use App\DataSource\API\EloquentCoinDataSource;
use Tests\TestCase;

class EloquentCoinDataSourceTest extends TestCase
{
    /**
     * @test
     **/
    public function aCoinIsNotFoundGivenAWrongCoinId()
    {
        $coinId = 'invalidCoinId';

        $eloquentCoinDataSource = new EloquentCoinDataSource();
        $response = $eloquentCoinDataSource->findCoinById($coinId);

        $this->assertNull($response);
    }

    /**
     * @test
     **/
    public function aCoinIsFoundGivenAValidCoinId()
    {
        $coinId = 80;

        $eloquentCoinDataSource = new EloquentCoinDataSource();
        $response = $eloquentCoinDataSource->findCoinById($coinId);

        $this->assertNotNull($response);
    }

}
