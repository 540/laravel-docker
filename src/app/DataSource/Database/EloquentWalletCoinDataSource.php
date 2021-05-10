<?php


namespace App\DataSource\Database;


use App\Models\Wallet;

class EloquentWalletCoinDataSource
{

    public function findWalletById($walletId)
    {
        return Wallet::query()->where('id', $walletId)->first();
    }
}
