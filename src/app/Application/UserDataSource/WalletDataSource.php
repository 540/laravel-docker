<?php

namespace App\Application\UserDataSource;

use App\Domain\Wallet;

Interface WalletDataSource
{
    public function addById(int $id): Wallet;
}
