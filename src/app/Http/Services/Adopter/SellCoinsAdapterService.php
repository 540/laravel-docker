<?php


namespace App\Http\Services\Adopter;


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
    public function execute($idCoin, $idWallet, $amount, $buyedBitcoins, $coinPrice, $operation): string
    {
        // Comprobar si hay esa cantidad de monetas
        $coinsBuyedAmount = $this->walletRepository->selectAmountBuyedCoins($idCoin,$idWallet);
        $coinsSelledAmount = $this->walletRepository->selectAmountSelledCoins($idCoin,$idWallet);
        $coinsAmount = $coinsBuyedAmount-$coinsSelledAmount;
echo $coinsAmount;
        if($coinsAmount < $amount){
            throw new \Exception('not enough coins to sell');
        }else{
            $wallet = $this->walletRepository->insertTransaction($idCoin, $idWallet,$buyedBitcoins, $amount, $coinPrice, $operation);

            if ($wallet == null) {
                throw new \Exception('wallet not found');
            }
        }
        return $wallet;
    }
}
