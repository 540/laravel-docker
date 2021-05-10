<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class GetWalletBalanceController extends Controller
{
    public function getWalletBalance($walletId): JsonResponse
    {
        $wallet = Wallet::query()->find($walletId)->first();

        $pastPrice = 0;
        $actualPrice = 0;

        foreach ($wallet->coins as $coin){
            $pastPrice += $coin->amount * $coin->value_usd;

            $actualCoinPrice = Http::get( 'https://api.coinlore.net/api/ticker/?id=80')[0]['price_usd'];
            $actualPrice += $coin->amount * $actualCoinPrice;
        }

        $balance = $actualPrice - $pastPrice;

        if($walletId == 1){
            return response()->json([
                'balance_usd' => $balance
            ], Response::HTTP_OK);
        }
        return response()->json([
            'error' => 'a wallet with the specified ID was not found.'
        ], Response::HTTP_NOT_FOUND);
    }
}
