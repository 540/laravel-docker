<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
class BuyCoinController extends BaseController
{
    public function __invoke(Request $request)
    {

        //$content = Request::all();
        //echo($request->coin_id);
        $url = "https://api.coinlore.net/api/ticker/?id=".$request->coin_id;
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);

        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec( $ch ));
        Cache::put('wallet'.$request->wallet_id.'-coin'.$request->coin_id,$response,600);

        return Cache::get('wallet'.$request->wallet_id.'-coin'.$request->coin_id);
    }
}

