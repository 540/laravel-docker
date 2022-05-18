<?php

namespace App\Application\EarlyAdopter;


use App\Application\UserDataSource\WalletDataSource;
use Illuminate\Http\Response;
use App\Domain\Wallet;
use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OpenNewWalletService
{

    private WalletDataSource $walletRepository;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletRepository = $walletDataSource;
    }


    public function execute(): Wallet
    {
        try {
            $wallet = $this->walletRepository->add();
        } catch (Exception) {
            throw new Exception('Service unavailable', Response::HTTP_NOT_FOUND);
        }

        return $wallet;
    }
}
