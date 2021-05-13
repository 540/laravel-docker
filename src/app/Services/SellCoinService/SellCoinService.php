<?php

namespace App\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinRepository;
use Exception;

class SellCoinService
{
    private EloquentCoinRepository $eloquentCoinRepository;

    public function __construct(EloquentCoinRepository $eloquentCoinRepository)
    {
        $this->eloquentCoinRepository = $eloquentCoinRepository;
    }

    public function execute(string $coinId, int $walletId, float $amountUSD)
    {
        try {
            $coin = $this->eloquentCoinRepository->findCoinById($coinId);
            // Find the coin in the given wallet
            $wallet
            // Update database selling $amountUSD coins
            return $coin;
        } catch (Exception $e) {
            throw new Exception("Error");
        }
    }
}
