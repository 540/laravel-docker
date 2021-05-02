<?php


namespace App\Infraestructure\Database;


use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletDatabase implements DatabaseManager
{

    public function set(string $field, string $value)
    {
        $user = DB::table('users')->where($field, $value)->first();
        if($user == null){
            return null;
        }

        $wallet = new Wallet();
    }

    public function get(string $field)
    {
        // TODO: Implement get() method.
    }
}
