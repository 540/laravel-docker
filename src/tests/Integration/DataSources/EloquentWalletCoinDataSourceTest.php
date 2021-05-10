<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentWalletCoinDataSource;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentWalletCoinDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function noWalletFoundForGivenWalletId()
    {
        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        $walletId = 'invalidWalletId';
        $result = $eloquentWalletCoinDataSource->findWalletById($walletId);

        $this->assertNull($result);
    }

    /**
     * @test
     **/
    public function walletIsFoundForAGivenWalletId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $eloquentWalletCoinDataSource = new EloquentWalletCoinDataSource();

        $result = $eloquentWalletCoinDataSource->findWalletById($wallet->id);

        echo $result->id;

        $this->assertEquals($wallet, $result);
    }
}
