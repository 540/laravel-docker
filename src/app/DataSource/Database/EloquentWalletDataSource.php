<?php


namespace App\DataSource\Database;

use App\Models\Wallet;
use Egulias\EmailValidator\Warning\LabelTooLong;
use Exception;
use Illuminate\Support\Facades\DB;

class EloquentWalletDataSource
{

    public function findWalletById($walletId)
    {
        return Wallet::query()->where('id', $walletId)->first();
    }

    public function createWalletByUserId($userId)
    {
        try {
            return DB::table('wallets')->insertGetId(['user_id' => $userId]);
        }catch (Exception $exception){
            return null;
        }
    }
}
