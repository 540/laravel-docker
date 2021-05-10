<?php


namespace App\DataSource\Database;


use App\Models\Wallet;
use App\Models\WalletCoin;
use Illuminate\Database\Eloquent\Collection;

class EloquentWalletCoinDataSource
{

    public function findWalletById($walletId)
    {
        return Wallet::query()->where('id', $walletId)->first();
    }
}
