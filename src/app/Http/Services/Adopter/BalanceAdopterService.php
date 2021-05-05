<?php


namespace App\Http\Services\Adopter;


use App\Infrastructure\Database\WalletDataSource;

class BalanceAdopterService
{
    /**
     * @var WalletDataSource
     */
    private $walletRepository;

    /**
     * isEarlyAdopterService constructor.
     * @param WalletDataSource $walletDataSource
     */
    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletRepository = $walletDataSource;
    }

    /**
     * @param $idWallet
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function execute($idWallet)
    {
        $typeCoins = $this->walletRepository->findTypeCoinsbyIdWallet($idWallet);

        if ($typeCoins == null) {
            throw new \Exception('wallet not found');
        }
        return $typeCoins;
    }

    /**
     * @param $idCoin
     * @param $idWallet
     * @return int|mixed
     * @throws \Exception
     */
    public function obtainBalance($idCoin, $idWallet){
        $coinsBuyedAmount = $this->walletRepository->selectAmountBuyedCoins($idCoin,$idWallet);
        $coinsSelledAmount = $this->walletRepository->selectAmountSelledCoins($idCoin,$idWallet);

        if ($coinsBuyedAmount == null && $coinsSelledAmount == null) {
            throw new \Exception('No operations. Wallet not found');
        }

        return $coinsBuyedAmount-$coinsSelledAmount;
    }
}
