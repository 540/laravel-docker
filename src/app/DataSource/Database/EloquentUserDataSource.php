<?php

namespace App\DataSource\Database;

use App\Exceptions\UserNotFoundException;
use App\Models\User;

class EloquentUserDataSource
{
    /**
     * @throws UserNotFoundException
     */
    public function findByEmail($email)
    {
        $user = User::query()->where('email', $email)->first();

        if (is_null($user))
            throw new UserNotFoundException();

        return $user;
    }
}
