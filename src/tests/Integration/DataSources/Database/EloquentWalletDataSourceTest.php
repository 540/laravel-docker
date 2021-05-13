<?php

namespace Tests\Integration\DataSources\Database;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\WalletNotFoundException;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentWalletDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function walletIsNotFoundGivenAnInvalidWalletId()
    {
        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();
        $walletId = 'invalidWalletId';

        $this->expectException(WalletNotFoundException::class);

        $eloquentWalletCoinDataSource->findWalletById($walletId);
    }

    /**
     * @test
     **/
    public function walletIsFoundGivenAnValidWalletId()
    {
        $wallet = Wallet::factory()->create()->first();

        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletCoinDataSource->findWalletById($wallet->id);

        $this->assertEquals($wallet, $result);
    }

    /**
     * @test
     **/
    public function walletIsNotCreatedGivenAnExistentUserId()
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
    public function walletIsCreatedGivenANonExistentUserId()
    {
        $userId = 'nonExistentUserId';
        $walletId = 1;

        $eloquentWalletCoinDataSource = new EloquentWalletDataSource();

        $result = $eloquentWalletCoinDataSource->createWalletByUserId($userId);

        $this->assertEquals($walletId, $result);
    }
}
