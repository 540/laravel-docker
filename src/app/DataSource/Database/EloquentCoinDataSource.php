<?php

namespace App\DataSource\Database;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentCoinDataSource
{
    /**
     * @param $coin_id
     * @return bool
     */
    public function thereIsCoinById($coin_id): bool
    {
        $result = DB::table('coins')
            ->select('coin_id')
            ->where('coin_id', $coin_id)
            ->get();

        return ($result != null);
    }
}
