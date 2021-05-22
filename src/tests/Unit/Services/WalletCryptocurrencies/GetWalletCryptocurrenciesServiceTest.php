<?php

namespace Tests\Unit\Services\WalletCryptocurrencies;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\WrongCoinIdException;
use App\Models\Coin;
use App\Models\Wallet;
use App\Services\WalletCryptocurrencies\GetWalletCryptocurrenciesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\Prophet;
use Tests\TestCase;

class GetWalletCryptocurrenciesServiceTest extends TestCase
{
    use RefreshDatabase;

    private $eloquentWalletCoinDataSource;
    private GetWalletCryptocurrenciesService $getWalletCryptocurrenciesService;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->eloquentWalletCoinDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->getWalletCryptocurrenciesService = new GetWalletCryptocurrenciesService($this->eloquentWalletCoinDataSource->reveal());
    }

    private function getWalletFromFactory(){
        return Wallet::factory()->create()->first();
    }

    /**
     * @test
     * @throws WalletNotFoundException
     */
    public function noCoinsFoundGivenAnInvalidWalletId()
    {
        $walletId = 'invalidWalletId';

        $this->eloquentWalletCoinDataSource->findWalletById($walletId)->willThrow(WrongCoinIdException::class);

        $this->expectException(WrongCoinIdException::class);

        $this->getWalletCryptocurrenciesService->execute($walletId);
    }

    /**
     * @test
     * @throws WalletNotFoundException
     */
    public function noCoinsFoundGivenAValidWalletId()
    {
        $wallet = $this->getWalletFromFactory();

        $expectedResult = [];

        $this->eloquentWalletCoinDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $result = $this->getWalletCryptocurrenciesService->execute($wallet->id);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     *
     * @throws WalletNotFoundException
     */
    public function aCoinIsFoundForAGivenWalletId()
    {
        $wallet = $this->getWalletFromFactory();

        $coins = Coin::factory(Coin::class)->make();

        $wallet->coins()->save($coins);

        $expectedResult = [];
        foreach ($wallet->coins as $coin){
            array_push($expectedResult, [
                'coin_id' => $coin->coin_id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]);
        }

        $this->eloquentWalletCoinDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $result = $this->getWalletCryptocurrenciesService->execute($wallet->id);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @throws WalletNotFoundException
     */
    public function twoCoinsAreFoundForAGivenWalletId()
    {
        $wallet = $this->getWalletFromFactory();

        $coins = Coin::factory(Coin::class)->count(2)->make();

        $wallet->coins()->saveMany($coins);

        $expectedResult = [];
        foreach ($wallet->coins as $coin){
            array_push($expectedResult, [
                'coin_id' => $coin->coin_id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->amount,
                'value_usd' => $coin->value_usd
            ]);
        }

        $this->eloquentWalletCoinDataSource->findWalletById($wallet->id)->shouldBeCalledOnce()->willReturn($wallet);

        $result = $this->getWalletCryptocurrenciesService->execute($wallet->id);

        $this->assertEquals($expectedResult, $result);
    }
}
