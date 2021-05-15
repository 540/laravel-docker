<?php


namespace App\DataSource\Database;


use App\Models\Coin;
use App\Models\User;
use App\Models\Wallet;
use phpDocumentor\Reflection\Types\Boolean;


class EloquentCoinBuyerDataSource
{
    public function findWallet($walletId): Boolean
    {
        $id = Wallet::query()->where('id', $walletId)->first();
        if (is_null($id)) {
            throw new Exception('Wallet not found');
        }
        return true;
    }

    public function findCoin($coinId): Coin
    {
        $coin = Coin::query()->where('coin_id', $coinId)->first();
        if (is_null($coin)) {
            throw new Exception('Coin not found');
        }
        return $coin;
    }

    public function insertCoin ($params) {
        DB::table('coins')->insert([
            'wallet_id' => $params[0],
            'coin_id' => $params[1],
            'name' => $params[2],
            'symbol' => $params[3],
            'amount' => $params[4],
            'value_usd' => $params[5]
        ]);
    }

    public function updateCoin ($coinId, $newAmount, $newValue) {
        DB::table('coins')->where('id', $coinId)
            ->update(['amount' => $newAmount]);
        DB::table('coins')->where('id', $coinId)
            ->update(['value_usd' => $newValue]);
    }

}
