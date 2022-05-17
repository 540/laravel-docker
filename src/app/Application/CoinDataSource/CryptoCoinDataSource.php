<?php

namespace App\Application\CoinDataSource;


use App\Domain\Coin;
use Exception;

class CryptoCoinDataSource implements CoinDataSource
{
    public function findByCoinId(string $coin_id)
    {
        $path = 'https://api.coinlore.net/api/ticker/?id=' . $coin_id;

        $ch = curl_init($path);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);

        if($data == null){
            return new Exception("A coin with the specified ID was not found.");
            //return new Coin("1","ERROR","ERR","0",1,"ERROR",1);
        }
        $data = $data[0];
        $name = $data->name;
        $symbol = $data->symbol;
        $amount = 0;
        $value_usd = floatval($data->price_usd);
        $name_id = $data->nameid;
        $rank = $data->rank;

        $Coin = new Coin($amount,$coin_id,$name,$name_id,$rank,$symbol,$value_usd);

        return $Coin;
    }
}
