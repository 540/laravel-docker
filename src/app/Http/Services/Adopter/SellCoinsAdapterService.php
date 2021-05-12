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
     * @param $idCoin
     * @param $idWallet
     * @param $amount
     * @param $operation
     * @return string
     * @throws \Exception
     */
    public function execute($idCoin, $idWallet, $amount, $operation): string
    {
        $coinPrice = $this->apiData->apiGetPrice($idCoin);
        if ($coinPrice == 0) {
            throw new \Exception('coin does not exist');
        }

        $usdSellPrice = $amount*$coinPrice;

        $coinsBuyedAmount = $this->walletRepository->selectAmountBoughtCoins($idCoin,$idWallet);
        if($coinsBuyedAmount >=0)
        {
            $coinsSelledAmount = $this->walletRepository->selectAmountSoldCoins($idCoin,$idWallet);

            $coinsAmount = $coinsBuyedAmount-$coinsSelledAmount;
            if($coinsAmount < $amount){
                throw new \Exception('not enough coins to sell');
            }else{
                $wallet = $this->walletRepository->insertTransaction($idCoin, $idWallet,$usdSellPrice, $amount, $coinPrice, $operation);
                var_dump($wallet);
                if ($wallet == null) {
                    throw new \Exception('transaction error');
                }
            }
            return "successful operation";
        }
        throw new \Exception ("wallet not found");
    }
}
