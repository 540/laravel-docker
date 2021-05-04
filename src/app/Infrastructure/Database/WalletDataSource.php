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
        return DB::table('wallet')->select('id_wallet')->where('id_user',$id)->max('id_wallet');  //max para obtener el último resultado
    }

    /**
     * @param $idWallet
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function findWalletDataByWalletId($idWallet){
        return DB::table('wallet')->where('id_wallet',$idWallet)->first();
    }

    /**
     * @param $idCoin
     * @param $idWallet
     * @param $amount
     * @param $buyedBitcoins
     * @param $idUser
     * @return string
     */
    public function updateByIdWallet($idCoin, $idWallet, $amount, $buyedBitcoins, $idUser): string
    {
        DB::table('wallet')->where('id_wallet',$idWallet)->where('id_user',$idUser)
            ->update([
                'id_wallet' => $idWallet,
                'id_user' => $idUser,
                'id_coin' => $idCoin,
                'buyed_amount' => $amount,
                'buy_price' => $buyedBitcoins
            ]);
        return "OK";
    }
}
