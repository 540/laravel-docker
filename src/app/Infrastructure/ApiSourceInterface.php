<?php


namespace App\Infrastructure;


interface ApiSourceInterface
{
    public function  apiGetPrice($idCoin);

    public function curl($url);
}
