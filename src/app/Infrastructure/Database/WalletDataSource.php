<?php


namespace App\Infrastructure\Database;


use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class WalletDataSource
{
    /**
     * WalletDataSource constructor.
     */
    public function __construct(){}


    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function insertById($id){
        DB::table('wallet')->insert([
            'id_user' => $id,
        ]);
        return $this->findWalletByUserId($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function findWalletByUserId($id){
        return DB::table('wallet')->select('id')->where('id_user',$id)->max('id');  //max para obtener el último resultado
    }

    /**
     * @param $idWallet
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function findWalletDataByWalletId($idWallet){
        $wallet = $this->findWallet($idWallet);
        if($wallet == true) {
            return DB::table('transaction')->where('id_wallet', $idWallet)->get();
        }
        return null;
    }

    /**
     * @param $idCoin
     * @param $idWallet
     * @param $amount
     * @param $buyedBitcoins
     * @param $coinPrice
     * @param $operation
     * @return string
     */
    public function insertTransaction($idCoin, $idWallet, $amount, $buyedBitcoins, $coinPrice, $operation): ?string
    {
        $wallet = $this->findWallet($idWallet);
        if($wallet == true){
            return DB::table('transaction')->insert([
                'id_wallet' => $idWallet,
                'id_coin' => $idCoin,
                'usd_buyed_amount' => $amount,
                'buyed_coins_amount' => $buyedBitcoins,
                'buyed_coins_usd_price'=>$coinPrice,
                'operation'=>$operation
            ]);
        }
        return -1;
    }

    /**
     * @param $idWallet
     * @return bool
     */
    private function findWallet($idWallet)
    {
        return DB::table('wallet')->where('id',$idWallet)->exists();
    }

    /**
     * @param $idCoin
     * @param $idWallet
     * @return int|mixed
     */
    public function selectAmountBoughtCoins($idCoin, $idWallet): int
    {
        $wallet = $this->findWallet($idWallet);
        if($wallet == true) {
            return DB::table('transaction')->where('id_coin', $idCoin)
                ->where('id_wallet', $idWallet)
                ->where('operation', 'buy')
                ->sum('buyed_coins_amount');
        }
        return -1;
    }

    /**
     * @param $idCoin
     * @param $idWallet
     * @return int|mixed|string
     */
    public function selectAmountSoldCoins($idCoin, $idWallet)
    {
        $wallet = $this->findWallet($idWallet);
        if($wallet == true) {
            return DB::table('transaction')->where('id_coin', $idCoin)
                ->where('id_wallet', $idWallet)
                ->where('operation', 'sell')
                ->sum('buyed_coins_amount');
        }
        return -1;
    }

    /**
     * @param $idWallet
     * @return \Illuminate\Support\Collection|int
     */
    public function findTypeCoinsbyIdWallet($idWallet)
    {
        $wallet = $this->findWallet($idWallet);
        if($wallet == true) {
            return DB::table('transaction')->select('id_coin')
                ->where('id_wallet', $idWallet)
                ->distinct()
                ->get();
        }
        return null;
    }
}
