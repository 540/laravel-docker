<?php

namespace App\DataSource\Database;

use App\Exceptions\WalletAlreadyExistsForUserException;
use App\Exceptions\WalletNotFoundException;
use App\Models\Wallet;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class EloquentWalletDataSource
{
    public function findWalletById($walletId)
    {
        $wallet = Wallet::query()->where('id', $walletId)->first();
        if($wallet == null)
        {
            throw new WalletNotFoundException();
        }
        return $wallet;
    }

    public function createWalletByUserId($userId)
    {
        try {
            return DB::table('wallets')->insertGetId(['user_id' => $userId]);
        }catch (QueryException $exception){
            throw new WalletAlreadyExistsForUserException();
        }
    }
}
