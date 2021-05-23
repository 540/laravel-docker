<?php

namespace Tests\EndToEnd;

use App\DataSource\API\CoinDataSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Doubles\FakeCoinLoreDataSourceManyCoins;
use Tests\TestCase;

class UseCaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function usualFlowWithOneWalletAndOneCoin()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSourceManyCoins::class);

        $walletId = 1;
        $coinId = '1';

        $this->postJson('api/wallet/open', [
            'user_id' => 'existentUserId'
        ]);

        $this->postJson('api/coin/buy', [
           'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 50
        ]);

        $this->postJson('api/coin/sell', [
           'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 25
        ]);

        $this->postJson('api/coin/buy', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 10
        ]);

        $cryptoResponse = $this->get('api/wallet/' . $walletId);
        $expectedCryptos = [
            [
                'coin_id' => $coinId,
                'name' => 'name1',
                'symbol' => 'symbol1',
                'amount' => 35,
                'value_usd' => 35
            ]
        ];

        $balanceResponse = $this->get('api/wallet/' . $walletId . '/balance');
        $expectedBalance = ['balance_usd' => 0];

        $cryptoResponse->assertStatus(Response::HTTP_OK)->assertJson($expectedCryptos);
        $balanceResponse->assertStatus(Response::HTTP_OK)->assertJson($expectedBalance);


    }

    /**
     * @test
     **/
    public function usualFlowWithOneWalletAndOneCoinSellingTheWholeCoin()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSourceManyCoins::class);

        $walletId = 1;
        $coinId = '1';

        $this->postJson('api/wallet/open', [
            'user_id' => 'existentUserId'
        ]);

        $this->postJson('api/coin/buy', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 50
        ]);

        $this->postJson('api/coin/sell', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 50
        ]);

        $cryptoResponse = $this->get('api/wallet/'. $walletId);
        $expectedCryptos = [];

        $balanceResponse = $this->get('api/wallet/' . $walletId . '/balance');
        $expectedBalance = ['balance_usd' => 0];

        $cryptoResponse->assertStatus(Response::HTTP_OK)->assertJson($expectedCryptos);
        $balanceResponse->assertStatus(Response::HTTP_OK)->assertJson($expectedBalance);
    }

    /**
     * @test
     **/
    public function usualFlowWithOneWalletMultipleCoins()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSourceManyCoins::class);

        $walletId = 1;
        $coinId1 = '1';
        $coinId2 = '2';

        $this->postJson('api/wallet/open', [
            'user_id' => 'existentUserId'
        ]);

        $this->postJson('api/coin/buy', [
            'coin_id' => $coinId1,
            'wallet_id' => $walletId,
            'amount_usd' => 50
        ]);

        $this->postJson('api/coin/buy', [
            'coin_id' => $coinId2,
            'wallet_id' => $walletId,
            'amount_usd' => 100
        ]);

        $this->postJson('api/coin/sell', [
            'coin_id' => $coinId1,
            'wallet_id' => $walletId,
            'amount_usd' => 25
        ]);

        $cryptosResponse = $this->get('api/wallet/'. $walletId);
        $expectedCryptos = [
            [
                'coin_id' => $coinId1,
                'name' => 'name1',
                'symbol' => 'symbol1',
                'amount' => 25,
                'value_usd' => 25
            ],
            [
                'coin_id' => $coinId2,
                'name' => 'name2',
                'symbol' => 'symbol2',
                'amount' => 100,
                'value_usd' => 100
            ]
        ];

        $balanceResponse = $this->get('api/wallet/' . $walletId . '/balance');
        $expectedBalance = ['balance_usd' => 0];

        $cryptosResponse->assertStatus(Response::HTTP_OK)->assertJson($expectedCryptos);
        $balanceResponse->assertStatus(Response::HTTP_OK)->assertJson($expectedBalance);
    }

    /**
     * @test
     **/
    public function usualFlowWithManyWallets()
    {
        $this->app->bind(CoinDataSource::class, FakeCoinLoreDataSourceManyCoins::class);

        $walletIdUser1 = 1;
        $coinId1 = '1';

        $this->postJson('api/wallet/open', [
            'user_id' => 'existentUserId1'
        ]);

        $this->postJson('api/coin/buy', [
            'coin_id' => $coinId1,
            'wallet_id' => $walletIdUser1,
            'amount_usd' => 50
        ]);

        $this->postJson('api/coin/sell', [
            'coin_id' => $coinId1,
            'wallet_id' => $walletIdUser1,
            'amount_usd' => 25
        ]);

        $expectedCryptosUser1 = [
            [
                'coin_id' => $coinId1,
                'name' => 'name1',
                'symbol' => 'symbol1',
                'amount' => 25,
                'value_usd' => 25
            ]
        ];
        $expectedBalanceUser1 = ['balance_usd' => 0];

        $walletIdUser2 = 2;
        $coinId2 = '2';

        $this->postJson('api/wallet/open', [
            'user_id' => 'existentUserId2'
        ]);
        $this->postJson('api/coin/buy', [
            'coin_id' => $coinId2,
            'wallet_id' => $walletIdUser2,
            'amount_usd' => 50
        ]);

        $expectedCryptosUser2 = [
            [
                'coin_id' => $coinId2,
                'name' => 'name2',
                'symbol' => 'symbol2',
                'amount' => 50,
                'value_usd' => 50
            ]
        ];
        $expectedBalanceUser2 = ['balance_usd' => 0];

        $cryptoResponseUser1 = $this->get('api/wallet/'. $walletIdUser1);
        $cryptoResponseUser2 = $this->get('api/wallet/'. $walletIdUser2);

        $balanceResponseUser1 = $this->get('api/wallet/' . $walletIdUser1 . '/balance');
        $balanceResponseUser2 = $this->get('api/wallet/' . $walletIdUser2 . '/balance');


        $cryptoResponseUser1->assertStatus(Response::HTTP_OK)->assertJson($expectedCryptosUser1);
        $balanceResponseUser1->assertStatus(Response::HTTP_OK)->assertJson($expectedBalanceUser1);

        $cryptoResponseUser2->assertStatus(Response::HTTP_OK)->assertJson($expectedCryptosUser2);
        $balanceResponseUser2->assertStatus(Response::HTTP_OK)->assertJson($expectedBalanceUser2);
    }
}
