<?php


namespace App\Services\OpenWalletService;


use App\Infraestructure\Database\DatabaseManager;
use App\Services\ServiceManager;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Cloner\Data;
use function PHPUnit\Framework\throwException;

class OpenWalletService
{
    private DatabaseManager $databaseManager;
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function execute($userId): string
    {
        $wallet = $this->databaseManager->set("userId",$userId);
        if($wallet == null){
            throw new Exception("Error");
        }
        return $wallet->getId();
    }

}
