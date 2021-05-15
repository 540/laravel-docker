<?php

namespace App\DataSource\Database;

use App\Models\Coin;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;

class EloquentCoinDataSource
{
    public function findCoinById(string $coinId, int $walletId)
    {
        $coin = Coin::query()
            ->where('coin_id', $coinId)
            ->where('wallet_id', $walletId)
            ->first();
        if(is_null($coin)) {
            throw new Exception('Coin not found');
        }
        return $coin;
    }

    public function sellCoinOperation($coin, int $walletId, float $newCoinAmount)
    {
        $affected = DB::table('coins')
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $walletId)
            ->update(['amount' => $newCoinAmount]);
        if($affected === 0) {
            throw new Exception("No coin sold");
        }
    }

    public function deleteCoin($id) {
        $deleted = DB::table('coins')->where('id', $id)->delete();
        if($deleted === 0) {
            throw new Exception("No coin sold");
        }
    }
}
