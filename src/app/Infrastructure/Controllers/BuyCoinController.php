<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class BuyCoinController extends BaseController
{
    public function __invoke()
    {


        $url = "https://api.coinlore.net/api/ticker/?id=90";
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);

        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );
        return $response;
    }
}

