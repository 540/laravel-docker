<?php

namespace App\Application\UserDataSource;

use App\Domain\User;
use phpDocumentor\Reflection\Types\Boolean;

Interface UserDataSource
{
    public function findById(int $id): Boolean;
}
