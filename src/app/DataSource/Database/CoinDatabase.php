<?php

namespace App\Infraestructure\Database;

use App\Models\Coin;
use Illuminate\Support\Facades\DB;

class CoinDatabase implements DatabaseManager
{
    public function set(string $field, string $value)
    {
        $coin = DB::table('coin')->where($field, $value)->first();
        if($coin == null)
        {
            return null;
        }
        $id = DB::table('coin')->update(
            ['id' => $value]
        );
        return new Coin($id, $value);
    }

    public function get(string $field)
    {
        // TODO: Implement get() method.
    }
}

