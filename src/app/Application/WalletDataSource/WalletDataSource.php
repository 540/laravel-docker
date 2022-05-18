<?php

namespace App\Application\WalletDataSource;

use App\Domain\Wallet;


Interface WalletDataSource
{
    public function add() : Wallet;
}
