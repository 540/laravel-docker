<?php

namespace App\Application\EarlyAdopter;


use App\Application\UserDataSource\WalletDataSource;
use Illuminate\Http\Response;
use App\Domain\Wallet;
use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class GetWalletService
{

    private WalletDataSource $walletRepository;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletRepository = $walletDataSource;
    }


    /**
     * @throws Exception
     */
    public function execute(int $wallet_id): Wallet
    {
        try {
            $wallet = $this->walletRepository->get($wallet_id);
        } catch (Exception $exception)  {
            throw new Exception($exception->getMessage(),$exception->getCode());
        }

        return $wallet;
    }
}
