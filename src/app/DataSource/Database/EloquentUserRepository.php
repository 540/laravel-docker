<?php

namespace App\Infraestructure\Database;

use Illuminate\Support\Facades\DB;

class EloquentUserRepository
{
    public function findByEmail($email){
        return $user = DB::table('users')->where('email', $email)->first();
    }
}
