<?php


namespace App\Http\Services\Adopter;


use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;

class BalanceAdopterService
{
    /**
     * @var WalletDataSource
     */
    private WalletDataSource $walletRepository;

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
     * @return \Illuminate\Support\Collection|int
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
    public function obtainBalance($idCoin, $idWallet)
    {
        $api = new ApiSource($idCoin);
        $coinData = $api->apiConnection();
        $coinPrice = $coinData[0]->price_usd;

        $coinsBoughtAmount = $this->walletRepository->selectAmountBoughtCoins($idCoin,$idWallet);

        $coinsSelledAmount = $this->walletRepository->selectAmountSelledCoins($idCoin,$idWallet);
        $coinsAmount = $coinsBoughtAmount-$coinsSelledAmount;
        return $coinsAmount * $coinPrice;

    }
}
