<?php


namespace App\DataSource\Database;


use App\Models\User;
use App\Models\Wallet;
use False\True;
use phpDocumentor\Reflection\Types\Boolean;

class CoinBuyerDataSource
{
    public function findWallet($wallet_id): Boolean
    {
        $id = Wallet::query()->where('id', $wallet_id)->first();
        if (is_null($id)) {
            throw new Exception('Wallet not found');
        }
        return true;
    }

    public function insertCoin () {

    }

}
