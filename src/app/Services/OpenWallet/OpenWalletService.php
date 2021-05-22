<?php


namespace App\Services\OpenWallet;


use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\WalletAlreadyExistsForUserException;

class OpenWalletService
{
    private EloquentWalletDataSource $eloquentWalletDataSource;

    public function __construct(EloquentWalletDataSource $eloquentWalletDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentWalletDataSource;
    }

    /**
     * @throws WalletAlreadyExistsForUserException
     */
    public function execute($userId): string
    {
        return $this->eloquentWalletDataSource->createWalletByUserId($userId);
    }

}
