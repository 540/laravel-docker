<?php

namespace App\Domain;

class Wallet
{
    private int $wallet_id;
    private array $coins;

    public function __construct(int $wallet_id, array $coins)
    {
        $this->wallet_id = $wallet_id;
        $this->coins = $coins;
    }

    /**
     * @return string
     */
    public function getWalletId(): string
    {
        return $this->wallet_id;
    }

    /**
     * @param string $wallet_id
     */
    public function setWalletId(string $wallet_id): void
    {
        $this->wallet_id = $wallet_id;
    }

    /**
     * @return array
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * @param array $coins
     */
    public function setCoins(array $coins): void
    {
        $this->coins = $coins;
    }

    public function toJson()
    {

    }


}
