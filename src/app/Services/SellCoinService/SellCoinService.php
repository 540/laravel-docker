<?php

namespace App\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinRepository;
use App\Models\Coin;
use Exception;

class SellCoinService
{
    private EloquentCoinRepository $eloquentCoinRepository;

    public function __construct(EloquentCoinRepository $eloquentCoinRepository)
    {
        $this->eloquentCoinRepository = $eloquentCoinRepository;
    }

    public function execute(string $coinId, string $walletId, float $amountUSD): Coin
    {
        $coin = $this->eloquentCoinRepository->findCoinById($coinId);

        if(is_null($coin)) {
            throw new Exception("Error");
        }

        // here goes the logic

        return $coin;
    }
}
