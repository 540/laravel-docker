<?php

namespace Tests\Integration\DataSources\Database;

use App\DataSource\Database\EloquentCoinDataSource;
use App\Exceptions\CannotCreateOrUpdateACoinException;
use App\Exceptions\CoinIdNotFoundInWalletException;
use App\Models\Coin;
use App\Models\Wallet;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EloquentCoinDataSourceTest extends TestCase
{
    use RefreshDatabase;
    private EloquentCoinDataSource $eloquentCoinDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eloquentCoinDataSource = new EloquentCoinDataSource();
    }

    /**
     * @test
     * @throws CoinIdNotFoundInWalletException
     */
    public function coinIsFoundInWallet()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $returnedCoin = ($this->eloquentCoinDataSource->findCoinById($coin->coin_id, $wallet->id));

        $this->assertEquals($returnedCoin->coin_id,$coin->coin_id);
        $this->assertEquals($returnedCoin->wallet_id,$coin->wallet_id);

    }

    /**
     * @test
     */
    public function coinIsNotFoundInWallet()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $this->expectException(CoinIdNotFoundInWalletException::class);

        $this->eloquentCoinDataSource->findCoinById(0,$wallet->id);
    }

    /**
     * @test
     * @throws CannotCreateOrUpdateACoinException
     */
    public function coinHasBeenUpdated()
    {
        $newAmount = 1.0;
        $newValue = 1.0;

        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $this->eloquentCoinDataSource->updateCoin($wallet->id, $coin->coin_id, $newAmount, $newValue);

        $updatedCoin = DB::table('coins')->where('wallet_id',$wallet->id)->where('coin_id', $coin->coin_id)->first();

        $this->assertEquals($updatedCoin->amount,$newAmount);
        $this->assertEquals($updatedCoin->value_usd,$newValue);
    }


    /**
     * @test
     */
    public function coinHasNotBeenUpdated()
    {
        $walletId = 'invalidWallet';
        $coinId = 'invalidCoinId';

        $this->expectException(CannotCreateOrUpdateACoinException::class);

        $this->eloquentCoinDataSource->updateCoin($walletId, $coinId,1,1);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function coinHasBeenCreated()
    {
        $coinId = '90';

        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $params = [$wallet->id,$coinId,'name','symbol',1,1];

        $this->eloquentCoinDataSource->insertCoin($params);

        $this->assertTrue(DB::table('coins')->where('wallet_id',$wallet->id)->where('coin_id', $coinId)->exists());
    }

    /**
     * @test
     */
    public function doesNotDeleteCoinIfCoinIdIsIncorrect() {

        $coinId = 'invalidId';

        $this->expectException(Exception::class);

        $this->eloquentCoinDataSource->deleteCoin($coinId);
    }

    /**
     * @test
     * @throws Exception
     */
    public function deletesCoinIfCoinIdIsCorrect() {

        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $this->eloquentCoinDataSource->deleteCoin($coin->id);
        $coin = Coin::query()
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $coin->wallet_id)
            ->first();

        $this->assertNull($coin);
    }

}
