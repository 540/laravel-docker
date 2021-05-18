<?php

namespace Tests\Unit\Services\CoinBuy;

use App\DataSource\API\CoinLoreCoinDataSource;
use App\DataSource\Database\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\CannotCreateOrUpdateACoinException;
use App\Exceptions\CoinIdNotFoundInWalletException;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\WrongCoinIdException;
use App\Models\Coin;
use App\Models\Wallet;
use App\Services\CoinBuy\CoinBuyerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Prophecy\Prophet;

class CoinBuyerServiceTest extends TestCase
{
    use RefreshDatabase;
    private Prophet $prophet;

    private  $eloquentCoinDataSource;
    private CoinBuyerService $coinBuyerService;
    private  $eloquentWalletDataSource;
    private  $coinLoreCoinDataSource;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet;
        $this->eloquentCoinDataSource = $prophet->prophesize(EloquentCoinDataSource::class);
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->coinLoreCoinDataSource = $prophet->prophesize(CoinLoreCoinDataSource::class);
        $this->coinBuyerService = new CoinBuyerService($this->eloquentCoinDataSource->reveal(),$this->eloquentWalletDataSource->reveal(),$this->coinLoreCoinDataSource->reveal());
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function coinIsNotFoundAndItIsCreated ()
    {
        $walletId = 1;
        $coinId = '90';
        $amount_usd = 1;
        $params = [$walletId,$coinId,'name','symbol',1,1];

        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $this->eloquentCoinDataSource->findCoin($coinId,$wallet->id)->shouldBeCalledOnce()->willThrow(new CoinIdNotFoundInWalletException());
        $this->coinLoreCoinDataSource->findCoinById($coinId)->shouldBeCalledOnce()->willReturn(['name'=>'name','symbol'=>'symbol','price_usd'=>1]);

        $this->eloquentCoinDataSource->insertCoin($params)->shouldBeCalledOnce()->will(function () use ($params) {
            DB::table('coins')->insert([
                'wallet_id' => $params[0],
                'coin_id' => $params[1],
                'name' => $params[2],
                'symbol' => $params[3],
                'amount' => $params[4],
                'value_usd' => $params[5]
            ]);
        });

        $this->coinBuyerService->execute($coinId,$wallet->id,$amount_usd);

        $coin = Coin::query()->where('wallet_id', $params[0])->where('coin_id',$params[1])->first();

        $this->assertEquals($coin->wallet_id,$params[0]);
        $this->assertEquals($coin->coin_id,$params[1]);
        $this->assertEquals($coin->name,$params[2]);
        $this->assertEquals($coin->symbol,$params[3]);
        $this->assertEquals($coin->amount,$params[4]);
        $this->assertEquals($coin->value_usd,$params[5]);

    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function coinIsNotFoundAndCannotBeCreated()
    {

        $walletId = 1;
        $coinId = '90';
        $amount_usd = 1;
        $params = [$walletId,$coinId,'name','symbol',1,1];

        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $this->eloquentCoinDataSource->findCoin($coinId,$wallet->id)->shouldBeCalledOnce()->willThrow(new CoinIdNotFoundInWalletException());
        $this->coinLoreCoinDataSource->findCoinById($coinId)->shouldBeCalledOnce()->willReturn(['name'=>'name','symbol'=>'symbol','price_usd'=>1]);

        $this->eloquentCoinDataSource->insertCoin($params)->shouldBeCalledOnce()->willThrow(new CannotCreateOrUpdateACoinException());

        $this->expectException(CannotCreateOrUpdateACoinException::class);

        $this->coinBuyerService->execute($coinId,$wallet->id,$amount_usd);

    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function coinIsFoundItIsUpdated()
    {
        $newAmount = 1.0;
        $newValue = 1.0;

        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $this->eloquentCoinDataSource->findCoin($coin->coin_id,$wallet->id)->shouldBeCalledOnce()->willReturn($coin);
        $this->coinLoreCoinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willReturn(['name'=>$coin->name,'symbol'=>$coin->symbol,'price_usd'=>1]);

        $this->eloquentCoinDataSource->updateCoin($wallet->id,$coin->coin_id,$newAmount,$newValue)->shouldBeCalledOnce()->will(function () use ($coin,$wallet,$newAmount,$newValue) {
             DB::table('coins')->where('coin_id', $coin->coin_id)->where('wallet_id',$wallet->id)
                ->update(['amount' => $newAmount, 'value_usd' => $newValue]);
        });

        $this->coinBuyerService->execute($coin->coin_id,$wallet->id,$newAmount);

        $coin = Coin::query()->where('wallet_id', $wallet->id)->where('coin_id',$coin->coin_id)->first();

        $this->assertEquals($coin->amount,$newAmount);
        $this->assertEquals($coin->value_usd,$newValue);

    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function coinIsFoundAndCannotBeUpdated()
    {

        $newAmount = 1.0;
        $newValue = 1.0;
        $amount_usd = 1.0;

        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);
        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $this->eloquentCoinDataSource->findCoin($coin->coin_id,$wallet->id)->shouldBeCalledOnce()->willReturn($coin);
        $this->coinLoreCoinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willReturn(['name'=>$coin->name,'symbol'=>$coin->symbol,'price_usd'=>1]);

        $this->eloquentCoinDataSource->updateCoin($wallet->id,$coin->coin_id,$newAmount,$newValue)->willThrow(new CannotCreateOrUpdateACoinException());

        $this->expectException(CannotCreateOrUpdateACoinException::class);

        $this->coinBuyerService->execute($coin->coin_id,$wallet->id,$amount_usd);

    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function walletIsNotFound ()
    {

        $amount_usd = 50;
        $coinId = 2;
        $invalidWalletId = 0;

        $this->eloquentWalletDataSource->findWalletById($invalidWalletId)->shouldBeCalledOnce()->willThrow(WalletNotFoundException::class);

        $this->expectException(WalletNotFoundException::class);

        $this->coinBuyerService->execute($coinId,$invalidWalletId,$amount_usd);

    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function wrongCoinIdProvided ()
    {

        $coinId = '999';
        $amount_usd = 50;

        $wallet = Wallet::factory(Wallet::class)->create()->first();

        $this->coinLoreCoinDataSource->findCoinById($coinId)->shouldBeCalledOnce()->willThrow(new WrongCoinIdException());

        $this->expectException(WrongCoinIdException::class);

        $this->coinBuyerService->execute($coinId,$wallet->id,$amount_usd);

    }

}
