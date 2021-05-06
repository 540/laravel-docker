<?php

namespace App\DataSource\Database;

use Illuminate\Support\Facades\DB;

class ElocuentWalletDataSource
{
    /**
     * @param $wallet_id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function findById($wallet_id){
        $wallet = DB::table('wallets')->select('coin_id', 'name', 'symbol', 'amount', 'value_usd')->where('wallet_id',$wallet_id)->first(); // Devuelve solo la primera tupla que coincida con wallet_id

        return $wallet;
    }
}

