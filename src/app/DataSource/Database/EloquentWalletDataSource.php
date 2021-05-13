<?php

namespace App\DataSource\Database;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentWalletDataSource
{
    /**
     * @param $wallet_id
     * @return Collection
     */
    public function findById($wallet_id): Collection
    {
        return DB::table('wallets')
                    ->join('walletscoins', 'wallets.wallet_id', '=', 'walletscoins.wallet_id')
                    ->join('coins', 'walletscoins.coin_id', '=', 'coins.coin_id')
                    ->select('coins.coin_id', 'name', 'symbol', 'amount')->where('wallets.wallet_id',$wallet_id)
                    ->get();
    }

    /**
     * @param $wallet_id
     * @return bool
     */
    public function thereIsWalletById($wallet_id): bool
    {
        $result = DB::table('wallets')
            ->select('wallet_id')
            ->where('wallet_id', $wallet_id)
            ->get();

        return ($result != null);
    }
}

