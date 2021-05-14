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
    public function getCoinsDataByWalletId($wallet_id): ?array
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
    public function getBalanceUsdByWalletId($wallet_id): ?float
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
    public function existsByWalletId($wallet_id): bool
    {
        $result = DB::table('wallets')
            ->select('wallet_id')
            ->where('wallet_id', $wallet_id)
            ->first();

        return ($result != null);
    }

    /**
     * @param $user_id
     * @return string
     */
    public function createWalletByUserId($user_id): string
    {
        $wallets = DB::table('wallets')
            ->select('wallet_id')
            ->where('wallet_id', 'LIKE', 'wallet-%')
            ->orderBy('wallet_id', 'desc')
            ->get()
            ->toArray();

        if (count($wallets) == 0 || $wallets == null) {
            $wallet_id = 'wallet-000000001';
        } else {
            $wallet_id = $wallets[0]->wallet_id;
            $wallet_id++;
        }

        DB::table('wallets')
            ->insert(['wallet_id' => $wallet_id, 'user_id' => $user_id, 'balance_usd' => 0]);

        return $wallet_id;
    }
}

