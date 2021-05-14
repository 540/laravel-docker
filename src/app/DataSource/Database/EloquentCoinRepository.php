<?php

namespace App\DataSource\Database;

use App\Models\Coin;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;

class EloquentCoinRepository
{
    public function findCoinById(string $coinId)
    {
        $coin = Coin::query()->where('coin_id', $coinId)->first();
        if(is_null($coin)) {
            throw new Exception('Coin not found');
        }
        return $coin;
    }

    public function findWallet($walletId)
    {
        $wallet = Wallet::query()->where('id', $walletId)->first();
        if(is_null($wallet)) {
            throw new Exception('Wallet not found');
        }
        return $wallet;
    }

    public function sellCoinOperation($coin, string $walletId, float $newCoinAmount)
    {
        DB::table('coins')
            ->where('id', $coin->coin_id)
            ->where('wallet_id', $walletId)
            ->update(['amount' => $newCoinAmount]);
    }

    public function deleteCoin($id) {
        DB::table('coins')->where('id', $id)->delete();
    }
}
