<?php

namespace App\DataSource\Database;

use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class EloquentWalletCoinDataSource
{
    public function getAmountByIds($coin_id, $wallet_id)
    {
        $result = DB::table('walletscoins')
            ->select('coin_id', 'wallet_id', 'amount')
            ->where('coin_id', $coin_id)
            ->where('wallet_id', $wallet_id)
            ->first();

        if ($result == null) {
            return null;
        }

        return $result->amount;
    }

    /**
     * @param $coin_id
     * @param $wallet_id
     * @param $amount
     * @param $amount_usd
     * @return void
     */
    public function buyCoins($coin_id, $wallet_id, $amount, $amount_usd): void
    {
        $oldAmount = $this->getAmountByIds($coin_id, $wallet_id);

        if ($oldAmount === null) {
            DB::table('walletscoins')
                ->insert(['coin_id' => $coin_id, 'wallet_id' => $wallet_id, 'amount' => 0]);
        }

        DB::table('walletscoins')
            ->where('coin_id', $coin_id)
            ->where('wallet_id', $wallet_id)
            ->increment('amount', $amount);

        DB::table('wallets')
            ->where('wallet_id', $wallet_id)
            ->decrement('balance_usd', $amount_usd);
    }

    /**
     * @param $coin_id
     * @param $wallet_id
     * @param $amount
     * @param $amount_usd
     * @return void
     */
    public function sellCoins($coin_id, $wallet_id, $amount, $amount_usd): void
    {
        $oldAmount = $this->getAmountByIds($coin_id, $wallet_id);

        if ($oldAmount !== null && $oldAmount >= $amount) {
            if ($oldAmount == $amount) {
                DB::table('walletscoins')
                    ->where('coin_id', $coin_id)
                    ->where('wallet_id', $wallet_id)
                    ->delete();
            } else {
                DB::table('walletscoins')
                    ->where('coin_id', $coin_id)
                    ->where('wallet_id', $wallet_id)
                    ->decrement('amount', $amount);
            }

            DB::table('wallets')
                ->where('wallet_id', $wallet_id)
                ->increment('balance_usd', $amount_usd);
            return;
        }

        throw new Exception("Insufficient amount to sell");
    }
}

