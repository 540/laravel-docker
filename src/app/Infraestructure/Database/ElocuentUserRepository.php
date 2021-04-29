<?php


namespace App\Infraestructure\Database;


class ElocuentUserRepository
{
    public function findByEmail($email){
        return $user = DB::table('susers')->where('email', $email)->first();
    }
}
