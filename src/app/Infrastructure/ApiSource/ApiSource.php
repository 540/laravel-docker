<?php


namespace App\Infrastructure\ApiSource;


class ApiSource
{
    /**
     * @var mixed
     */
    private $coinData;

    /**
     * ApiSource constructor.
     */
    public function __construct($idCoin)
    {
        $this->coinData = json_decode($this->curl("https://api.coinlore.net/api/ticker/?id=".$idCoin));
    }

    /**
     * @return mixed
     */
    public function apiConnection()
    {
        return $this->coinData;
    }

    /**
     * @param $url
     * @return bool|string
     */
    private function curl($url)
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
