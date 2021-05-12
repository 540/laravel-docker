<?php

namespace App\Services\Wallet;

use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use Exception;

class WalletService
{
    /**
     * @var EloquentWalletDataSource
     * @var CoinLoreDataSource
     */
    private EloquentWalletDataSource $eloquentWalletRepository;
    private CoinLoreDataSource $coinLoreRepository;

    /**
     * WalletService constructor.
     * @param EloquentWalletDataSource $eloquentWalletRepository
     * @param CoinLoreDataSource $coinLoreRepository
     */
    public function __construct(EloquentWalletDataSource $eloquentWalletRepository, CoinLoreDataSource $coinLoreRepository)
    {
        $this->eloquentWalletRepository = $eloquentWalletRepository;
        $this->coinLoreRepository = $coinLoreRepository;
    }

    /**
     * @param string $wallet_id
     * @return array
     * @throws Exception
     */
    public function execute(string $wallet_id)
    {
        // Hacer una consulta
        $wallet = $this->eloquentWalletRepository->findById($wallet_id); // Se puede acceder a los atributos de $wallet

        // Si no devuelve nada
        if ($wallet == null) {
            throw new Exception('Wallet not found');
        }

        $walletArray = $wallet->toArray();

        for ($i = 0; $i < count($walletArray); $i++) {
            $coinId = $walletArray[$i]->coin_id;
            $amount = $walletArray[$i]->amount;

            $price = $this->coinLoreRepository->findUsdPriceByCoinId($coinId);

            $valueUsd = $amount * $price;
            $walletData = json_decode(json_encode($walletArray[$i]), true);
            $walletArray[$i] = array_merge($walletData, array("value_usd" => $valueUsd));
        }

        return $walletArray;
    }
}
