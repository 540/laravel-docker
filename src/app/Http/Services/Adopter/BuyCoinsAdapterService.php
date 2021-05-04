<?php


namespace App\Http\Services\Adopter;


use App\Infrastructure\Database\WalletDataSource;

class BuyCoinsAdapterService
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
     * @return string
     * @throws \Exception
     */
    public function execute($idCoin, $idWallet, $amount, $buyedBitcoins, $idUser)
    {
        // Hacer una consulta
        $wallet = $this->walletRepository->updateByIdWallet($idCoin, $idWallet, $amount, $buyedBitcoins, $idUser);
        // Si no devuelve nada
        if ($wallet == null) {
            throw new \Exception('wallet not found');
        }
        return $wallet;
    }
}


