<?php

namespace App\DataSource\Database;

use Illuminate\Support\Facades\DB;

class EloquentUserDataSource
{
    /**
     * @param $user_id
     * @return bool
     */
    public function existsByUserId($user_id): bool
    {
        $result = DB::table('users')
            ->select('user_id')
            ->where('user_id', $user_id)
            ->first();

        return ($result != null);
    }
}
