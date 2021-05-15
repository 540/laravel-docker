<?php

namespace App\DataSource\Database;

use App\Models\Coin;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;

class EloquentCoinSellerDataSource
{
    public function findCoinById(string $coinId, int $walletId)
    {
        $coin = Coin::query()
            ->where('id', $coinId)
            ->where('wallet_id', $walletId)
            ->first();
        if(is_null($coin)) {
            throw new Exception('Coin not found');
        }
        return $coin;
    }

    public function sellCoinOperation($coin, int $walletId, float $newCoinAmount)
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
