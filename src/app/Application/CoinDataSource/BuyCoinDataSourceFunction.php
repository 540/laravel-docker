<?php

namespace App\Application\CoinDataSource;


use Exception;
use Illuminate\Http\Response;

class BuyCoinDataSourceFunction implements BuyCoinDataSource
{
    public function findByCoinId(string $coin_id)
    {
        $url = 'https://api.coinlore.net/api/ticker/?id=' . $coin_id;

        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);

        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec( $ch ));
        if(empty($response))
        {
            return new Exception("A coin with the specified ID was not found.");
        }
        return $response;
    }
}
