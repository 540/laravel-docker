<?php

namespace App\Application\UserDataSource;

use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;


class WalletDataSourceFunctions implements WalletDataSource
{
    public function add() : Wallet {
        $wallet = new Wallet();
        $wallet_id = 1;

        while (Cache::has($wallet_id)) {
            $wallet_id++;
        }
        $wallet->data['wallet_id'] = $wallet_id;
        Cache::put($wallet_id,$wallet);
        return $wallet;
    }
}
