<?php

namespace Tests\Integration\DataSources\Database;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\WalletAlreadyExistsForUserException;
use App\Exceptions\WalletNotFoundException;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentWalletDataSourceTest extends TestCase
{
    use RefreshDatabase;

    private EloquentWalletDataSource $eloquentWalletDataSource;

    protected function setUp():void
    {
        parent::setUp();
        $this->eloquentWalletDataSource = new EloquentWalletDataSource();
    }

    /**
     * @test
     **/
    public function walletIsNotFoundGivenAnInvalidWalletId()
    {
        $this->expectException(WalletNotFoundException::class);

        $walletId = 'invalidWalletId';
        $this->eloquentWalletDataSource->findWalletById($walletId);
    }

    /**
     * @test
     **/
    public function walletIsFoundGivenAnValidWalletId()
    {
        $wallet = Wallet::factory()->create()->first();

        $result = $this->eloquentWalletDataSource->findWalletById($wallet->id);

        $this->assertEquals($wallet, $result);
    }

    /**
     * @test
     **/
    public function walletIsNotCreatedGivenAnExistentUserId()
    {
        Wallet::factory()->create();

        $this->expectException(WalletAlreadyExistsForUserException::class);

        $userId = 'existentUserId';
        $this->eloquentWalletDataSource->createWalletByUserId($userId);
    }

    /**
     * @test
     **/
    public function walletIsCreatedGivenANonExistentUserId()
    {
        $userId = 'nonExistentUserId';

        $result = $this->eloquentWalletDataSource->createWalletByUserId($userId);

        $expectedWalletId = 1;
        $this->assertEquals($expectedWalletId, $result);
    }
}
