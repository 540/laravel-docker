<?php

namespace App\Application\EarlyAdopter;

use App\Application\UserDataSource\WalletDataSource;
use Exception;

class OpenNewWalletService
{

    private $walletRepository;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletRepository = $walletDataSource;
    }


    public function execute($id){
        if($id == null){
            throw new Exception('A user with the specified ID was not found');
        }

        try {
            $wallet = $this->walletRepository->addById($id);
        } catch (Exception) {
            throw new Exception("Service unavailable");
        }

        if($wallet == null){
            throw new Exception('Error: response status is 404');
        }
        return $wallet;
    }
}
