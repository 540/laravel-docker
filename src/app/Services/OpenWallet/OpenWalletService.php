<?php


namespace App\Services\OpenWallet;


use App\DataSource\Database\EloquentWalletDataSource;

class OpenWalletService
{
    private EloquentWalletDataSource $eloquentWalletDataSource;

    public function __construct(EloquentWalletDataSource $eloquentWalletDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentWalletDataSource;
    }

    public function execute($userId): string
    {
        return $this->eloquentWalletDataSource->createWalletByUserId($userId);
    }

}
