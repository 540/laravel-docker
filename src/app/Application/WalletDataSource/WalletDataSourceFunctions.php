<?php

namespace App\Application\WalletDataSource;

use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;


class WalletDataSourceFunctions implements WalletDataSource
{
    public function add() : Wallet {
        $wallet_id = 1;

        while (Cache::has('wallet'.$wallet_id)) {
            $wallet_id++;
        }
        $wallet = new Wallet($wallet_id,[]);
        Cache::put('wallet'.$wallet_id,$wallet);
        return $wallet;
    }
}
