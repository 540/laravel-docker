<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GetWalletCryptocurrenciesController extends Controller
{
    public function getWalletCryptocurrencies($user_id):JsonResponse{
        return response()->json([
            'error' => 'a wallet with the specified ID was not found.'
        ], Response::HTTP_NOT_FOUND);
    }
}
