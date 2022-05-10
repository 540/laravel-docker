<?php

namespace App\Application\UserDataSource;

use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;

Class WalletDataSource
{

    public function addById(int $user_id) {

        $wallet = new Wallet();
        $wallet_id = 1;
        $wallet->data['user_id'] = $user_id;

        while (Cache::has($wallet_id)) {
            $wallet_id++;
        }
        $wallet->data['wallet_id'] = $wallet_id;
        Cache::put($wallet_id,$wallet);
        return $wallet;
    }
}
