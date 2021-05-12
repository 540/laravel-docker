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
     * @var ApiSource
     */
    private ApiSource $apiData;

    /**
     * isEarlyAdopterService constructor.
     * @param WalletDataSource $walletDataSource
     * @param ApiSource $apiData
     */
    public function __construct(WalletDataSource $walletDataSource, ApiSource $apiData)
    {
        $this->walletRepository = $walletDataSource;
        $this->apiData = $apiData;
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
        $coinPrice = $this->apiData->apiGetPrice($idCoin);

        $coinsBoughtAmount = $this->walletRepository->selectAmountBoughtCoins($idCoin,$idWallet);
        $coinsSelledAmount = $this->walletRepository->selectAmountSoldCoins($idCoin,$idWallet);

        $coinsAmount = $coinsBoughtAmount-$coinsSelledAmount;
        return $coinsAmount * $coinPrice;

    }
}
