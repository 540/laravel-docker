<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentUserDataSource;
use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletCoin;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentUserDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        User::factory(User::class)->create();
        Wallet::factory(Wallet::class)->create();
        Coin::factory(Coin::class)->create();
        WalletCoin::factory(WalletCoin::class)->create();
    }

    /**
     * @test
     * @throws Exception
     */
    public function notExistsByUserId()
    {
        $expectedResult = false;

        $eloquentUserDataSource = new EloquentUserDataSource();

        $result = $eloquentUserDataSource->existsByUserId('error-user');
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function existsByUserId()
    {
        $expectedResult = true;

        $eloquentUserDataSource = new EloquentUserDataSource();

        $result = $eloquentUserDataSource->existsByUserId('factory-user');
        $this->assertEquals($expectedResult, $result);
    }
}
