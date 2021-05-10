<?php

namespace App\Services\SellCoinService;

use App\Infraestructure\Database\DatabaseManager;
use Exception;

class SellCoinService
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function execute($coinId, $walletId, $amountUSD)
    {
        $coin = $this->databaseManager->set("coinId", $coinId);
        //$coin = $this->databaseManager->set("walletId", $walletId);
        //$coin = $this->databaseManager->set("amountUSD", $amountUSD);

        if($coin === null)
        {
            throw new Exception("Error");
        }
        return $coin;
    }
}
