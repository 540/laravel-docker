<?php

namespace App\DataSource\Api;

use Illuminate\Support\Facades\Http;

class CoinLoreApi
{
    public function findCoinById($coinId)
    {
        return Http::get("https://api.coinlore.net/api/ticker/?id=80");
    }
}
