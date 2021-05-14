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
            $previousTotalCoinValueUSD = $coin->amount * $coin->valueUSD;
            if($previousTotalCoinValueUSD > $amountUSD) {
                $newTotalCoinValueUSD = $previousTotalCoinValueUSD - $amountUSD;
                $newCoinAmount = $newTotalCoinValueUSD / $coin->valueUSD;
                $this->eloquentCoinRepository->sellCoinOperation($coin, $walletId, $newCoinAmount);
            }
            elseif($previousTotalCoinValueUSD === $amountUSD) {
                $this->eloquentCoinRepository->deleteCoin($coin->id);
            }
            return $coin;
        }
        catch(Exception $e) {
            throw new Exception("Error");
        }
    }
}
