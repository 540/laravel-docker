<?php

namespace App\DataSource\Database;

use Illuminate\Support\Facades\DB;

class EloquentCoinDataSource
{
    /**
     * @param $coin_id
     * @return bool
     */
    public function existsByCoinId($coin_id): bool
    {
        $result = DB::table('coins')
            ->select('coin_id')
            ->where('coin_id', $coin_id)
            ->first();

        return ($result != null);
    }
}
