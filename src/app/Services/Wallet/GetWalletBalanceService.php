<?php

namespace App\Services\Wallet;

use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use Exception;

class GetWalletBalanceService
{
    /**
     * @var EloquentWalletDataSource
     * @var CoinLoreDataSource
     */
    private EloquentWalletDataSource $eloquentWalletRepository;
    private CoinLoreDataSource $coinLoreRepository;

    /**
     * GetWalletBalanceService constructor.
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
     * @return float
     * @throws Exception
     */
    public function execute(string $wallet_id): float
    {
        if (!$this->eloquentWalletRepository->existsByWalletId($wallet_id)) {
            throw new Exception('Wallet not found');
        }

        $balanceUsd = $this->eloquentWalletRepository->getBalanceUsdByWalletId($wallet_id);
        if (is_null($balanceUsd)) {
            throw new Exception('Wallet balance not found');
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
            $balanceUsd +=  $valueUsd;
        }

        return round($balanceUsd, 2);
    }
}
