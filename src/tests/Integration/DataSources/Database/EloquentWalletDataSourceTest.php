<?php

namespace Tests\Integration\DataSources\Database;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentWalletDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function noWalletFoundForGivenWalletId()
    {
        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $walletId = 'invalidWalletId';
        $result = $eloquentWalletCoinDataSource->findWalletById($walletId);

        $this->assertNull($result);
    }

    /**
     * @test
     **/
    public function walletIsFoundForAGivenWalletId()
    {
        $wallet = Wallet::factory()->create()->first();

        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletCoinDataSource->findWalletById($wallet->id);

        $this->assertEquals($wallet, $result);
    }

    /**
     * @test
     **/
    public function walletIsNotCreatedForGivenWalletId()
    {
        Wallet::factory()->create();

        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $userId = 'existingUserId';
        $result = $eloquentWalletDataSource->createWalletByUserId($userId);

        $this->assertNull($result);
    }

    /**
     * @test
     **/
    public function walletIsCreatedForAGivenValidUserId()
    {
        $userId = 'validUserId';
        $walletId = 1;

        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletCoinDataSource->createWalletByUserId($userId);

        $this->assertEquals($walletId, $result);
    }
}
