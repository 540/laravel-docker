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
    public function __construct(
        EloquentWalletDataSource $eloquentWalletRepository,
        CoinLoreDataSource $coinLoreRepository
    ) {
        $this->eloquentWalletRepository = $eloquentWalletRepository;
        $this->coinLoreRepository = $coinLoreRepository;
    }

    /**
     * @param string $wallet_id
     * @return array|null
     * @throws Exception
     */
    public function execute(string $wallet_id): ?array
    {
        $wallet = $this->eloquentWalletRepository->findById($wallet_id);

        if (is_null($wallet) || count($wallet) == 0) {
            throw new Exception('Wallet not found');
        }

        for ($i = 0; $i < count($wallet); $i++) {
            $coinId = $wallet[$i]->coin_id;
            $amount = $wallet[$i]->amount;

            $price = $this->coinLoreRepository->findUsdPriceByCoinId($coinId);
            if (is_null($price)) {
                throw new Exception('External API failure');
            }

            $valueUsd = $amount * $price;
            $walletData = json_decode(json_encode($wallet[$i]), true);
            $wallet[$i] = array_merge($walletData, array("value_usd" => $valueUsd));
        }

        return $wallet;
    }
}
