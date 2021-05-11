<?php


namespace App\Http\Services\Adopter;


use App\Infrastructure\ApiSource\ApiSource;
use App\Infrastructure\Database\WalletDataSource;
use function PHPUnit\Framework\throwException;

class SellCoinsAdapterService
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
     * @param $idCoin
     * @param $idWallet
     * @param $amount
     * @param $buyedBitcoins
     * @param $coinPrice
     * @param $operation
     * @return string
     * @throws \Exception
     */
    public function execute($idCoin, $idWallet, $amount, $operation): string
    {
        $api = new ApiSource($idCoin);
        $coinData = $api->apiConnection();

        $coinPrice = $coinData[0]->price_usd;
        $usdSellPrice = $amount*$coinPrice;

        $coinsBuyedAmount = $this->walletRepository->selectAmountBoughtCoins($idCoin,$idWallet);
        if($coinsBuyedAmount >=0)
        {
            $coinsSelledAmount = $this->walletRepository->selectAmountSelledCoins($idCoin,$idWallet);

            $coinsAmount = $coinsBuyedAmount-$coinsSelledAmount;
            if($coinsAmount < $amount){
                throw new \Exception('not enough coins to sell');
            }else{
                $wallet = $this->walletRepository->insertTransaction($idCoin, $idWallet,$usdSellPrice, $amount, $coinPrice, $operation);
                if ($wallet == null) {
                    throw new \Exception('transaction error');
                }
            }
            return "successful operation";
        }
        throw new \Exception ("wallet not found");
    }
}