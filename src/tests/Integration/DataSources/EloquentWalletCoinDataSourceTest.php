<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\User;
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
        $user = User::factory(User::class)->create()->first();

        $wallet = Wallet::factory()->make();

        $user->wallet()->save($wallet);

        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletCoinDataSource->findWalletById($user->wallet->id);

        $this->assertEquals(Wallet::query()->find($user->wallet->id), $result);
    }
}
