<?php

namespace App\Infraestructure\Database;

use App\Models\Wallet;
use Exception;

class EloquentWalletRepository
{
    public function findWalletById(string $walletId)
    {
        $wallet = Wallet::query()->where('wallet_id', $walletId)->first();

        if (is_null($wallet)) {
            throw new Exception('Wallet not found');
        }

        return $wallet;
    }
}
