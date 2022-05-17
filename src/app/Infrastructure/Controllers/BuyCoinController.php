<?php

namespace App\Infrastructure\Controllers;

use Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
class BuyCoinController extends BaseController
{


    public function __invoke(Request $request): JsonResponse
    {

        if( is_null($request->coin_id))
        {
            return response()->json([
                'error' => "coin_id mandatory"
            ], Response::HTTP_BAD_REQUEST);
        }
        elseif( is_null($request->wallet_id))
        {
            return response()->json([
                'error' => "wallet_id mandatory"
            ], Response::HTTP_BAD_REQUEST);
        }
        elseif( is_null($request->amount_usd))
        {
            return response()->json([
                'error' => "amount_usd mandatory"
            ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_NOT_FOUND);
        }

        Cache::put($request->wallet_id,$response,600);

        return response()->json([
        $response
        ], Response::HTTP_OK);
    }
}

