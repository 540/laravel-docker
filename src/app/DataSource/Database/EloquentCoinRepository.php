<?php

namespace App\DataSource\Database;

use App\Models\Coin;
use Exception;
use Illuminate\Support\Facades\DB;

class EloquentCoinRepository
{
    public function findCoinById(string $coinId)
    {
        $coin = Coin::query()->where('coin_id', $coinId)->first();

        if (is_null($coin)) {
            throw new Exception('Coin not found');
        }

        return $coin;
    }

    public function sellCoinOperation($coin, string $walletId, float $amountUSD)
    {
        $previousTotalCoinValueUSD = $coin->amount * $coin->valueUSD;
        if($previousTotalCoinValueUSD >= $amountUSD) {
            $newTotalCoinValueUSD = $previousTotalCoinValueUSD - $amountUSD;
            try {
                $newCoinAmount = $newTotalCoinValueUSD / $coin->valueUSD;
            }
            catch(Exception $e) {
                return false;
            }
            DB::table('coins')
                ->where('id', $coin->coin_id)
                ->where('wallet_id', $walletId)
                ->update(['amount' => $newCoinAmount]);
        }
        return true;
    }
}
