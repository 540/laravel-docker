<?php

namespace App\Models;

class Wallet
{
    private String $userId;
    private String $id;

    public function __construct(String $userId, String $walletId){
        $this->userId = $userId;
        $this->id = $walletId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
