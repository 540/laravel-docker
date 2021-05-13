<?php

namespace App\DataSource\Database;

use Exception;
use Illuminate\Support\Facades\DB;

class EloquentWalletDataSource
{
    /**
     * @param $wallet_id
     * @return array|null
     * @throws Exception
     */
    public function findById($wallet_id): ?array
    {
        return DB::table('wallets')
                    ->join('walletscoins', 'wallets.wallet_id', '=', 'walletscoins.wallet_id')
                    ->join('coins', 'walletscoins.coin_id', '=', 'coins.coin_id')
                    ->select('coins.coin_id', 'name', 'symbol', 'amount')
                    ->where('wallets.wallet_id', $wallet_id)
                    ->get()
                    ->toArray();
    }

    /**
     * @param $wallet_id
     * @return float|null
     */
    public function getBalanceUsdById($wallet_id): ?float
    {
        $result = DB::table('wallets')
            ->select('wallet_id', 'user_id', 'balance_usd')
            ->where('wallet_id', $wallet_id)
            ->first();

        if ($result == null) {
            return null;
        }

        return $result->balance_usd;
    }

    /**
     * @param $wallet_id
     * @return bool
     */
    public function thereIsWalletById($wallet_id): bool
    {
        $result = DB::table('wallets')
            ->select('wallet_id')
            ->where('wallet_id', $wallet_id)
            ->get();

        return ($result != null);
    }
}

