<?php

namespace Tests\Unit\Services\WalletBalance;

use App\DataSource\API\CoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\CannotDeleteACoinException;
use App\Exceptions\CannotUpdateACoinException;
use App\Exceptions\CoinIdNotFoundInWalletException;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\WrongCoinIdException;
use App\Models\Coin;
use App\Models\Wallet;
use App\Services\WalletBalance\GetWalletBalanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\Prophet;
use Tests\TestCase;

class GetWalletBalanceServiceTest extends TestCase
{
    use RefreshDatabase;

    private $eloquentWalletDataSource;
    private $coinDataSource;
    private GetWalletBalanceService $getWalletBalanceService;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->coinDataSource = $prophet->prophesize(CoinDataSource::class);
        $this->getWalletBalanceService = new GetWalletBalanceService($this->eloquentWalletDataSource->reveal(), $this->coinDataSource->reveal());
    }

    /**
     * @test
     * @throws WalletNotFoundException
     */
    public function noWalletIsFoundGivenAnInvalidWalletId()
    {
        $walletId = 1;
        $this->eloquentWalletDataSource->findWalletById($walletId)->shouldBeCalledOnce()->willThrow(WalletNotFoundException::class);

        $this->expectException(WalletNotFoundException::class);

        $this->getWalletBalanceService->execute($walletId);
    }

    /**
     * @test
     * @throws WalletNotFoundException
     */
    public function coinIsNotFoundGivenAWrongCoinId()
    {
        $wallet = Wallet::factory(Wallet::class)->create()->first();
        $coin = Coin::factory(Coin::class)->make();
        $wallet->coins()->save($coin);

        $coin = Coin::query()->where('wallet_id', $wallet->id)->first();

        $this->eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);
        $this->coinDataSource->findCoinById($coin->coin_id)->shouldBeCalledOnce()->willThrow(WrongCoinIdException::class);

        $this->expectException(WrongCoinIdException::class);

        $this->getWalletBalanceService->execute($wallet->id);
    }

    /**
     * @test
     * @throws WalletNotFoundException
     */
    public function balanceIsProvidedGivenAValidWalletId()
    {
        $wallet = Wallet::factory()->create()->first();
        $coins = Coin::factory(Coin::class)->count(2)->make();
        $wallet->coins()->saveMany($coins);

        $coins = Coin::query()->where('wallet_id', $wallet->id)->get();

        $this->eloquentWalletDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $expectedBalance = 0;
        foreach ($coins as $coin)
        {
            $this->coinDataSource->findCoinById($coin->coin_id)->willReturn([
                'price_usd' => 30
            ]);
            $expectedBalance += 30 - ($coin->value_usd);
        }

        $result = $this->getWalletBalanceService->execute($wallet->id);

        $this->assertEquals($expectedBalance, $result);
    }
}
