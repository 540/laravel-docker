<?php


namespace App\Services\OpenWallet;


use App\DataSource\Database\EloquentWalletDataSource;
use Exception;

class OpenWalletService
{
    private EloquentWalletDataSource $eloquentWalletDataSource;

    public function __construct(EloquentWalletDataSource $eloquentWalletDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentWalletDataSource;
    }

    public function execute($userId): string
    {
        $wallet = $this->eloquentWalletDataSource->createWalletByUserId($userId);
        if($wallet == null){
            throw new Exception("Error asdlkfjlskjdf");
        }
        return $wallet->id;
    }

}
