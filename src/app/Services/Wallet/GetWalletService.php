<?php

namespace App\Services\Wallet;

use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use Exception;

class GetWalletService
{
    /**
     * @var EloquentWalletDataSource
     * @var CoinLoreDataSource
     */
    private EloquentWalletDataSource $eloquentWalletRepository;
    private CoinLoreDataSource $coinLoreRepository;

    /**
     * GetWalletService constructor.
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
        if (!$this->eloquentWalletRepository->existsByWalletId($wallet_id)) {
            throw new Exception('Wallet not found');
        }

        $wallet = $this->eloquentWalletRepository->getCoinsDataByWalletId($wallet_id);
        if (is_null($wallet)) {
            throw new Exception('Wallet not found');
        }

        for ($i = 0; $i < count($wallet); $i++) {
            $coinId = $wallet[$i]->coin_id;
            $amount = $wallet[$i]->amount;

            $price = $this->coinLoreRepository->getUsdPriceByCoinId($coinId);
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
