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
            // here goes the logic
            return $coin;
        } catch (Exception $e) {
            throw new Exception("Error");
        }
    }
}
