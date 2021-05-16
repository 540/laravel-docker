<?php

namespace App\DataSource\External;

use Illuminate\Support\Facades\Http;

class CoinLoreDataSource
{
    /**
     * @param $coinId
     * @return string|null
     */
    public function getUsdPriceByCoinId($coinId): ?string
    {
        $response = Http::get('https://api.coinlore.net/api/ticker/?id=' . $coinId);
        if ($response->body() == null) {
            return null;
        }
        return json_decode($response->body())[0]->price_usd;
    }
}
