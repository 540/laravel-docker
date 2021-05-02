<?php


namespace App\Services\OpenWalletService;


use App\Infraestructure\Database\DatabaseManager;
use App\Services\ServiceManager;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Cloner\Data;

class OpenWalletService implements ServiceManager
{
    private DatabaseManager $databaseManager;
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function getResponse(Request $request): string
    {
        $wallet = $this->databaseManager->set("userId", $request->get('userId'));
        if($wallet == null){
            return "user not found";
        }
        return $wallet->getId();
    }
}
