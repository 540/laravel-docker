<?php

namespace App\DataSource\Database;

use App\Exceptions\WalletNotFoundException;
use App\Models\Wallet;
use Exception;
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
        }catch (Exception $exception){
            return null;
        }
    }
}
