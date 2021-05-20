<?php

namespace Tests\Services\SellCoinService;

use App\DataSource\API\CoinLoreCoinDataSource;
use App\DataSource\Database\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\CoinIdNotFoundInWalletException;
use App\Models\Coin;
use App\Models\Wallet;
use App\Services\CoinBuy\CoinBuyerService;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Prophecy\Prophet;

class SellCoinServiceTest extends TestCase
{
    use RefreshDatabase;
    private Prophet $prophet;

    private  $eloquentCoinDataSource;
    private SellCoinService $sellCoinService;
    private  $eloquentWalletDataSource;
    private  $coinLoreCoinDataSource;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet;
        $this->eloquentCoinDataSource = $prophet->prophesize(EloquentCoinDataSource::class);
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->coinLoreCoinDataSource = $prophet->prophesize(CoinLoreCoinDataSource::class);
        $this->sellCoinService = new SellCoinService($this->eloquentCoinDataSource->reveal(),$this->eloquentWalletDataSource->reveal(),$this->coinLoreCoinDataSource->reveal());
    }

    /**
     * @test
     * @throws CoinIdNotFoundInWalletException
     */
    public function coinIsNotFoundIfCoinIdIsNotCorrect()
    {
        $coinId = "invalidCoinId";
        $walletId = 1;
        $amountUSD = 0;

        $this->eloquentCoinDataSource->findCoinById($coinId, $walletId)->willThrow(Exception::class);

        $this->expectException(Exception::class);

        $this->sellCoinService->execute($coinId, $walletId, $amountUSD);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsExceptionIfCannotSellPartOfTheCoinsForGivenCorrectId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $amountUSD = 0.5;
        $price_usd = 1;

        $this->eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce();
        $this->eloquentCoinDataSource->findCoinById($coin->coin_id, $coin->wallet_id)->shouldBeCalledOnce()->willReturn($coin);
        $this->coinLoreCoinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willReturn(['price_usd'=>$price_usd]);
        $amountToSell = $amountUSD/$price_usd;
        $this->eloquentCoinDataSource->updateCoin($coin->wallet_id, $coin->coin_id, ($coin->amount-$amountToSell), ($coin->value_usd-$amountUSD))->shouldBeCalledOnce()->willThrow(Exception::class);

        $this->expectException(Exception::class);

        $this->sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);
    }

    /**
     * @test
     * @throws CoinIdNotFoundInWalletException
     * @throws \App\Exceptions\CannotCreateOrUpdateACoinException
     */
    public function sellsPartOfTheCoinsForGivenId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $amountUSD = 0.5;
        $price_usd = 1;

        $this->eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce();
        $this->eloquentCoinDataSource->findCoinById($coin->coin_id, $coin->wallet_id)->shouldBeCalledOnce()->willReturn($coin);
        $this->coinLoreCoinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willReturn(['price_usd'=>$price_usd]);
        $amountToSell = $amountUSD/$price_usd;
        $newAmount = $coin->amount-$amountToSell;
        $newValue = $coin->value_usd-$amountUSD;

        $this->eloquentCoinDataSource
            ->updateCoin($coin->wallet_id, $coin->coin_id, $newAmount, $newValue)
            ->shouldBeCalledOnce()
            ->will(function () use ($coin, $newAmount, $newValue) {
                DB::table('coins')
                    ->where('coin_id', $coin->coin_id)
                    ->where('wallet_id', $coin->wallet_id)
                    ->update(['amount' => $newAmount, 'value_usd'=>$newValue]);
            });

        $this->sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);

        $updatedSoldCoin = DB::table('coins')->where('id', $coin->id)->first();

        $this->assertEquals($newAmount, $updatedSoldCoin->amount);
        $this->assertEquals($newValue, $updatedSoldCoin->value_usd);
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsExceptionIfCannotSellEveryCoinForGivenCorrectId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $amountUSD = 1.5;
        $price_usd = 1;

        $this->eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce();
        $this->eloquentCoinDataSource->findCoinById($coin->coin_id, $coin->wallet_id)->shouldBeCalledOnce()->willReturn($coin);
        $this->coinLoreCoinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willReturn(['price_usd'=>$price_usd]);

        $this->eloquentCoinDataSource
            ->deleteCoin($coin)
            ->shouldBeCalledOnce()
            ->willThrow(Exception::class);

        $this->expectException(Exception::class);

        $this->sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);
    }

    /**
     * @test
     * @throws Exception
     */
    public function sellsEveryCoinForGivenId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $amountUSD = 1.5;
        $price_usd = 1;

        $this->eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce();
        $this->eloquentCoinDataSource->findCoinById($coin->coin_id, $coin->wallet_id)->shouldBeCalledOnce()->willReturn($coin);
        $this->coinLoreCoinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willReturn(['price_usd'=>$price_usd]);

        $this->eloquentCoinDataSource
            ->deleteCoin($coin->id)
            ->shouldBeCalledOnce()
            ->will(function () use ($coin) {
                DB::table('coins')->where('id', $coin->id)->delete();
            });

        $this->sellCoinService->execute($coin->coin_id, $coin->wallet_id, $amountUSD);

        $deletedSoldCoin = DB::table('coins')->where('id', $coin->id)->first();

        $this->assertNull($deletedSoldCoin);
    }
}
