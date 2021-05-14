<?php


namespace App\Infrastructure\ApiSourceInterface;


interface ApiSourceInterface
{
    public function  apiGetPrice($idCoin);

    public function curl($url);
}
