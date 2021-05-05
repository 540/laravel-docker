<?php


namespace App\Infrastructure\Database;


use Illuminate\Support\Facades\DB;

class WalletDataSource
{
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
        return DB::table('transaction')->where('id_wallet',$idWallet)->get();
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
    public function insertTransaction($idCoin, $idWallet, $amount, $buyedBitcoins, $coinPrice, $operation): string
    {
        DB::table('transaction')->insert([
            'id_wallet' => $idWallet,
            'id_coin' => $idCoin,
            'usd_buyed_amount' => $amount,
            'buyed_coins_amount' => $buyedBitcoins,
            'buyed_coins_usd_price'=>$coinPrice,
            'operation'=>$operation
        ]);

        return "Successful Operation";
    }

    /**
     * @param $idCoin
     * @param $idWallet
     * @return int|mixed
     */
    public function selectAmountBuyedCoins($idCoin, $idWallet): int
    {
        return DB::table('transaction')->where('id_coin',$idCoin)
            ->where('id_wallet',$idWallet)
            ->where('operation','buy')
            ->sum('buyed_coins_amount');
    }

    /**
    * @param $idCoin
    * @param $idWallet
    * @return int|mixed
    */
    public function selectAmountSelledCoins($idCoin, $idWallet): int
    {
        return DB::table('transaction')->where('id_coin',$idCoin)
            ->where('id_wallet',$idWallet)
            ->where('operation','sell')
            ->sum('buyed_coins_amount');
    }
}
