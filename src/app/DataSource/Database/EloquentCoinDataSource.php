<?php


namespace App\DataSource\Database;

use App\Exceptions\CannotCreateOrUpdateACoinException;
use App\Exceptions\CoinIdNotFoundInWalletException;
use Exception;
use Illuminate\Support\Facades\DB;



class EloquentCoinDataSource
{

    /**
     * @throws CoinIdNotFoundInWalletException
     */
    public function findCoinById($coinId, $walletId)
    {
        $coin = DB::table('coins')->where('wallet_id',$walletId)->where('coin_id', $coinId)->first();

        if (is_null($coin)) {
            throw new CoinIdNotFoundInWalletException();
        }

        return $coin;
    }

    /**
     * @throws Exception
     */
    public function insertCoin ($params) {
        $rowsAffected = DB::table('coins')->insert([
            'wallet_id' => $params[0],
            'coin_id' => $params[1],
            'name' => $params[2],
            'symbol' => $params[3],
            'amount' => $params[4],
            'value_usd' => $params[5]
        ]);
        if ($rowsAffected == 0) {
            throw new CannotCreateOrUpdateACoinException();
        }
    }

    /**
     * @throws CannotCreateOrUpdateACoinException
     */
    public function updateCoin ($walletId, $coinId, $newAmount, $newValue) {
        $rowsAffected = DB::table('coins')->where('coin_id', $coinId)->where('wallet_id',$walletId)
            ->update(['amount' => $newAmount, 'value_usd' => $newValue]);
        if ($rowsAffected == 0)
            throw new CannotCreateOrUpdateACoinException();
    }

    public function deleteCoin($id) {
        $deletedRows = DB::table('coins')->where('id', $id)->delete();
        if($deletedRows === 0) {
            throw new Exception("No coin sold");
        }
    }

}
