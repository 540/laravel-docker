<?php

namespace App\Models;

class Coin
{
    private string $coinId;
    private string $name;
    private string $symbol;
    private float $amount;
    private float $valueUSD;

    public function __construct(string $coinId, string $name, string $symbol, float $amount, float $valueUSD)
    {
        $this->coinId = $coinId;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->valueUSD = $valueUSD;
    }

    public function getCoinId(): string
    {
        return $this->coinId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getValueUSD(): float
    {
        return $this->valueUSD;
    }
}
