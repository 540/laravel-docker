<?php


namespace App\DataSource\API;


class CoinLoreApi
{

    public function findCoinById($coin_id) {

        return Http::get('https://api.coinlore.net/api/ticker/?id=' . $coin_id)[0];

    }
}
