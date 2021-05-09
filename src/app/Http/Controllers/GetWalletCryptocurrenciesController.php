<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GetWalletCryptocurrenciesController extends Controller
{

    public function getWalletCryptocurrencies($user_id):JsonResponse{
        if($user_id == 1){
            return response()->json([
                'coin_id' => 1,
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'amount' => 1,
                'value_usd' => 1
            ], Response::HTTP_OK);
        }
        return response()->json([
            'error' => 'a wallet with the specified ID was not found.'
        ], Response::HTTP_NOT_FOUND);
    }
}
