<?php


namespace App\Infraestructure\Database;


use Illuminate\Support\Facades\DB;

class ElocuentUserRepository
{
    public function findByEmail($email){
        return $user = DB::table('users')->where('email', $email)->first();
    }
}
