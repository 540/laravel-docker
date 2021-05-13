<?php

namespace App\Services\Wallet;

use App\DataSource\Database\EloquentUserDataSource;
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
    private EloquentUserDataSource $eloquentUserRepository;
    private CoinLoreDataSource $coinLoreRepository;

    /**
     * WalletService constructor.
     * @param EloquentWalletDataSource $eloquentWalletRepository
     * @param EloquentUserDataSource $eloquentUserRepository
     * @param CoinLoreDataSource $coinLoreRepository
     */
    public function __construct(
        EloquentWalletDataSource $eloquentWalletRepository,
        EloquentUserDataSource $eloquentUserRepository,
        CoinLoreDataSource $coinLoreRepository
    ) {
        $this->eloquentWalletRepository = $eloquentWalletRepository;
        $this->coinLoreRepository = $coinLoreRepository;
        $this->eloquentUserDataSource = $eloquentUserRepository;
    }

    /**
     * @param string $wallet_id
     * @return array|null
     * @throws Exception
     */
    public function execute(string $wallet_id): ?array
    {
        $wallet = $this->eloquentWalletRepository->findById($wallet_id);
        if (is_null($wallet)) {
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

    /**
     * @param string $wallet_id
     * @return float
     * @throws Exception
     */
    public function executeBalance(string $wallet_id): float
    {
        try {
            $wallet = $this->execute($wallet_id);
        } catch (Exception $exception) {
            throw $exception;
        }

        $balanceUsd = $this->eloquentWalletRepository->getBalanceUsdById($wallet_id);
        for ($i = 0; $i < count($wallet); $i++) {
            $balanceUsd += $wallet[$i]['value_usd'];
        }

        return $balanceUsd;
    }

    /**
     * @param string $user_id
     * @return string
     * @throws Exception
     */
    public function executeOpen(string $user_id): string
    {
        if (!$this->eloquentUserDataSource->thereIsUserById($user_id)) {
            throw new Exception('User not found');
        }

        return $this->eloquentWalletRepository->openWalletByUserId($user_id);
    }
}
