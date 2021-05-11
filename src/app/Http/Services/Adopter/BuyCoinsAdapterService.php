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
     * BuyCoinsAdapterService constructor.
     * @param WalletDataSource $walletDataSource
     */
    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletRepository = $walletDataSource;
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
        $api = new ApiSource($idCoin);
        $coinData = $api->apiConnection();

        $coinPrice = $coinData[0]->price_usd;
        $buyedCoins = $amount/$coinPrice;

        $wallet = $this->walletRepository->insertTransaction($idCoin, $idWallet, $amount, $buyedCoins, $coinPrice, $operation);
        if ($wallet == null) {
            throw new \Exception('wallet not found');
        }
        return "Successful Operation";
    }


}


