<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\User;
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
        $user = User::factory(User::class)->create()->first();

        $wallet = Wallet::factory()->make();

        $user->wallet()->save($wallet);

        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletCoinDataSource->findWalletById($user->wallet->id);

        $this->assertEquals(Wallet::query()->find($user->wallet->id), $result);
    }

    /**
     * @test
     **/
    public function walletIsNotCreatedForGivenWalletId()
    {
        $eloquentWalletDataSource = new EloquentWalletDataSource();

        $userId = 'invalidUserId';
        $result = $eloquentWalletDataSource->createWalletByUserId($userId);

        $this->assertNull($result);
    }

    /**
     * @test
     **/
    public function walletIsCreatedForAGivenWalletId()
    {
        $user = User::factory(User::class)->create()->first();

        $wallet = new Wallet();

        $wallet->id = 1;
        $wallet->user_id = $user->id;

        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletCoinDataSource->createWalletByUserId($user->id);

        $this->assertEquals($wallet->id, $result->id);
        $this->assertEquals($wallet->user_id, $result->user_id);
    }
}
