<?php

namespace App\Infrastructure;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;

class FakeUserDataSource implements UserDataSource
{

    public function findByEmail(string $email): User
    {
        if($email === 'a@a.com'){

            return new User(999, $email);
        }
        return new User(3000, $email);
    }
}
