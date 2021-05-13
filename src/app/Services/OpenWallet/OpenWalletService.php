<?php


namespace App\Services\OpenWallet;


use App\DataSource\Database\EloquentWalletDataSource;
use App\Errors\Errors;
use App\Exceptions\WalletAlreadyExistsForUserException;
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
        $walletId = $this->eloquentWalletDataSource->createWalletByUserId($userId);
        if($walletId == null){
            throw new WalletAlreadyExistsForUserException();
        }
        return $walletId;
    }

}
