<?php

namespace App\DataSource\Database;

use App\Models\User;
use Exception;

class EloquentUserDataSource
{
    public function findByEmail($email): User
    {
        $user = User::query()->where('email', $email)->first();
        if (is_null($user)) {
            throw new Exception('User not found');
        }
        return $user;
    }
}
