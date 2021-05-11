<?php


namespace App\Http\Services\Adopter;


use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;

class BuyCoinsAdapterService
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
     * BuyCoinsAdapterService constructor.
     * @param WalletDataSource $walletDataSource
     * @param ApiSource $apiData
     */
    public function __construct(WalletDataSource $walletDataSource, ApiSource $apiData)
    {
        $this->walletRepository = $walletDataSource;
        $this->apiData = $apiData;
    }

    /**
     * @param $idCoin
     * @param $idWallet
     * @param $amount
     * @param $operation
     * @return string
     * @throws \Exception
     */
    public function execute($idCoin, $idWallet, $amount, $operation): string
    {
        $coinData = $this->apiData->apiConnection($idCoin);
        if ($coinData == 0) {
            throw new \Exception('coin does not exist');
        }

        $coinPrice = $coinData[0]->price_usd;
        $buyedCoins = $amount/$coinPrice;

        $wallet = $this->walletRepository->insertTransaction($idCoin, $idWallet, $amount, $buyedCoins, $coinPrice, $operation);
        if ($wallet == null) {
            throw new \Exception('wallet not found');
        }
        return "Successful Operation";
    }


}


