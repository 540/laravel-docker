<?php

namespace App\Application\CoinDataSource;


use App\Domain\Coin;

class CryptoCoinDataSource implements CoinDataSource
{
    public function findByCoinId(string $coin_id):Coin
    {
        $path = 'https://api.coinlore.net/api/ticker/?id=' . $coin_id;

        $ch = curl_init($path);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);

        $data = $data[0];
        $name = $data->name;
        $symbol = $data->symbol;
        $amount = 0;
        $value_usd = floatval($data->price_usd);
        $name_id = $data->nameid;
        $rank = $data->rank;

        $Coin = new Coin($coin_id,$name,$symbol,$amount,$value_usd,$name_id,$rank);

        return $Coin;
    }
}
