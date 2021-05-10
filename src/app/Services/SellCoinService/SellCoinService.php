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

    public function execute(string $coinId, string $walletId, float $amountUSD)
    {
        $coin = $this->eloquentCoinRepository->findByCoinId($coinId);
        //$coin = $this->databaseManager->set("walletId", $walletId);
        //$coin = $this->databaseManager->set("amountUSD", $amountUSD);

        if($coin === null)
        {
            throw new Exception("Error");
        }
        return $coin;
    }
}
