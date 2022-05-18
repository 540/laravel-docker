<?php

namespace App\Application\UserDataSource;

use App\Domain\Wallet;


Interface WalletDataSource
{
    public function add() : Wallet;
    public function get(int $wallet_id) : Wallet;
}
