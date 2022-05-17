<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
class BuyCoinController extends BaseController
{
    public function __invoke(Request $request): JsonResponse
    {

        //$content = Request::all();
        //echo($request->coin_id);
        if( (int)$request->coin_id == 0)
        {
            return response()->json([
                'error' => "coin_id tiene que ser un entero y mayor que 0"
            ], 400);
        }
        elseif( (int)$request->wallet_id == 0)
        {
            return response()->json([
                'error' => "wallet_id tiene que ser un entero y mayor que 0"
            ], 400);
        }
        elseif((int)$request->amount_usd == 0)
        {
            return response()->json([
                'error' => "amount_usd tiene que ser un entero y mayor que 0"
            ], 400);
        }
        $url = "https://api.coinlore.net/api/ticker/?id=".$request->coin_id;
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);

        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec( $ch ));
        if(empty($response))
        {
            return response()->json([
                'error' => "A coin with the specified ID was not found."
            ], 404);
        }

        Cache::put('wallet'.$request->wallet_id.'-coin'.$request->coin_id,$response,600);

        return response()->json([
            $response
        ], Response::HTTP_OK);
    }
}

