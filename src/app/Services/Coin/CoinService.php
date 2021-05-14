<?php

namespace App\Services\Coin;

use App\DataSource\Database\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\DataSource\External\CoinLoreDataSource;
use Exception;

class CoinService
{
    /**
     * @var EloquentWalletDataSource
     * @var CoinLoreDataSource
     */
    private EloquentCoinDataSource $eloquentCoinRepository;
    private EloquentWalletDataSource $eloquentWalletRepository;
    private EloquentWalletCoinDataSource $eloquentWalletCoinRepository;
    private CoinLoreDataSource $coinLoreRepository;

    /**
     * CoinService constructor.
     * @param EloquentCoinDataSource $eloquentCoinRepository
     * @param EloquentWalletDataSource $eloquentWalletRepository
     * @param EloquentWalletCoinDataSource $eloquentWalletCoinRepository
     * @param CoinLoreDataSource $coinLoreRepository
     */
    public function __construct(
        EloquentCoinDataSource $eloquentCoinRepository,
        EloquentWalletDataSource $eloquentWalletRepository,
        EloquentWalletCoinDataSource $eloquentWalletCoinRepository,
        CoinLoreDataSource $coinLoreRepository
    ) {
        $this->eloquentCoinRepository = $eloquentCoinRepository;
        $this->eloquentWalletRepository = $eloquentWalletRepository;
        $this->coinLoreRepository = $coinLoreRepository;
        $this->eloquentWalletCoinRepository = $eloquentWalletCoinRepository;
    }

    /**
     * @param string $coinId
     * @param string $walletId
     * @param float $amountUsd
     * @return float
     * @throws Exception
     */
    private function calculateCoinsAmount(string $coinId, string $walletId, float $amountUsd): float
    {
        if (!$this->eloquentCoinRepository->existsByCoinId($coinId)) {
            throw new Exception('Coin not found');
        }
        if (!$this->eloquentWalletRepository->existsByWalletId($walletId)) {
            throw new Exception('Wallet not found');
        }

        $price = $this->coinLoreRepository->getUsdPriceByCoinId($coinId);
        if ($price == null) {
            throw new Exception('External API failure');
        }

        return round($amountUsd / $price, 8); // Unidad minima del Bitcoin (Satoshi)
                                                            // 1 BTC = 100.000.000 Sats
                                                            // 0,00000001 BTC = 1 Sats
    }

    /**
     * @param string $coinId
     * @param string $walletId
     * @param float $amountUsd
     * @return void
     * @throws Exception
     */
    public function executeBuy(string $coinId, string $walletId, float $amountUsd): void
    {
        try {
            $amount = $this->calculateCoinsAmount($coinId, $walletId, $amountUsd);
            if ($amount == 0) {
                throw new Exception('Insufficient amount to buy');
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        $this->eloquentWalletCoinRepository->buyCoins($coinId, $walletId, $amount, $amountUsd);
    }

    /**
     * @param string $coinId
     * @param string $walletId
     * @param float $amountUsd
     * @return void
     * @throws Exception
     */
    public function executeSell(string $coinId, string $walletId, float $amountUsd): void
    {
        try {
            $amount = $this->calculateCoinsAmount($coinId, $walletId, $amountUsd);
            if ($amount == 0) {
                throw new Exception('Insufficient amount to sell');
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        $this->eloquentWalletCoinRepository->sellCoins($coinId, $walletId, $amount, $amountUsd);
    }
}
