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
        return DB::table('wallet')->where('id_user',$id)->first();
    }
}
