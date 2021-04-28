<?php


namespace App\Infrastructure\Database;


use Illuminate\Support\Facades\DB;

class ElocuentUserDataSource
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function findById($id){
        $user = DB::table('users')->where('id',$id)->first(); // devuelve solo la primera tupla que coincida
        return $user;
    }
}
