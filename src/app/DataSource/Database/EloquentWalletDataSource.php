<?php


namespace App\DataSource\Database;


use App\Models\User;
use App\Models\Wallet;

class EloquentWalletDataSource
{

    public function findWalletById($walletId)
    {
        return Wallet::query()->where('id', $walletId)->first();
    }

    public function createWalletByUserId($userId)
    {
        $user = User::query()->find($userId);
        if($user == null){
            return null;
        }
        $user->first()->wallet()->save(new Wallet());
        return $user->wallet;
    }
}
