<?php

namespace App\Services\OpenWalletService;

use App\Infraestructure\Database\DatabaseManager;
use Exception;

class OpenWalletService
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function execute($userId): string
    {
        $wallet = $this->databaseManager->set("id",$userId);
        if($wallet == null)
        {
            throw new Exception("Error");
        }
        return $wallet->getId();
    }
}
