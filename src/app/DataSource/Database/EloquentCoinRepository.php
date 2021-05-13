<?php

namespace App\DataSource\Database;

use App\Models\Coin;
use Exception;

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
}
