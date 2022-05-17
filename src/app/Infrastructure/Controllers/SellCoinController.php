<?php

namespace App\Infrastructure\Controllers;
use App\Domain\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
class SellCoinController extends BaseController
{
    public function __invoke(Request $request): JsonResponse
    {

        //$content = Request::all();
        //echo($request->coin_id);
        if( is_null($request->coin_id))
        {
            return response()->json([
                'error' => "coin_id mandatory"
            ], 400);
        }
        elseif( is_null($request->wallet_id))
        {
            return response()->json([
                'error' => "wallet_id mandatory"
            ], 400);
        }
        elseif( is_null($request->amount_usd))
        {
            return response()->json([
                'error' => "amount_usd mandatory"
            ], 400);
        }

        $response = Cache::get($request->wallet_id);
        if(empty($response->coin_id))
        {
            return response()->json([
                'error' => "A coin with the specified ID was not found."
            ], 404);
        }

        Cache::put($request->wallet_id,$response,600);

        return response()->json([
        $response
        ], Response::HTTP_OK);
    }
}

