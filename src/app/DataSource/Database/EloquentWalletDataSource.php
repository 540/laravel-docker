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
}

