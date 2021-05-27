<?php


namespace App\Infrastructure\ApiSource;


use App\Infrastructure\ApiSourceInterface\ApiSourceInterface;

class FakeApiSource implements ApiSourceInterface
{

    public function apiGetPrice($idCoin)
    {
        return(50000);
    }

    public function curl($url)
    {
        // TODO: Implement curl() method.
    }
}
