<?php

namespace Tests;

class CoinTest
{
    public string $coin_id;
    public string $name;
    public string $symbol;
    public float $amount;
    public string $name_id;

    /**
     * @param string $coin_id
     * @param string $name
     * @param string $symbol
     * @param float $amount
     * @param string $name_id
     */
    public function __construct(string $coin_id, string $name, string $symbol, float $amount, string $name_id)
    {
        $this->coin_id = $coin_id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->name_id = $name_id;
    }


}
