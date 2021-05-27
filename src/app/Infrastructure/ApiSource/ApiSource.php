<?php


namespace App\Infrastructure\ApiSource;

use App\Infrastructure\ApiSourceInterface;

class ApiSource implements ApiSourceInterface
{
    /**
     * @param $idCoin
     * @return mixed
     */
    public function apiGetPrice($idCoin)
    {
        $coinData = json_decode($this->curl("https://api.coinlore.net/api/ticker/?id=".$idCoin));
        return $coinData[0]->price_usd;
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function curl($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_VERBOSE => false,
            CURLOPT_USERAGENT => 'Coinlore PHP/API',
            CURLOPT_POST => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 65
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }
}
