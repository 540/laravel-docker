<?php

namespace App\Services\SellCoinService;

use App\Infraestructure\Database\DatabaseManager;

class SellCoinService
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function execute($coin_id, $wallet_id, $amount_usd)
    {
        
    }
}
