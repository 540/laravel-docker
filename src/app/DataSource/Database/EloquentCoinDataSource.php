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
        $affectedRows = DB::table('coins')
            ->where('coin_id', $coin->coin_id)
            ->where('wallet_id', $walletId)
            ->update(['amount' => $newCoinAmount]);
        if($affectedRows === 0) {
            throw new Exception("No coin sold");
        }
    }

    public function deleteCoin($id) {
        $deletedRows = DB::table('coins')->where('id', $id)->delete();
        if($deletedRows === 0) {
            throw new Exception("No coin sold");
        }
    }
}
