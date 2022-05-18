<?php

namespace App\Application\UserDataSource;

use App\Domain\Wallet;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;


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

    public function get(int $wallet_id): Wallet
    {
        if(Cache::has('wallet'.$wallet_id)) {
            if(Cache::has('wallet'.$wallet_id)) {
                return Cache::get('wallet'.$wallet_id);
            }
            throw new Exception('Service unavailable', Response::HTTP_NOT_FOUND);
        }
        throw new Exception('A wallet with the specified ID was not found.', Response::HTTP_NOT_FOUND);

    }


}
