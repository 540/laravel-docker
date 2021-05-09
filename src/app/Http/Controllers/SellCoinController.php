<?php

namespace App\Http\Controllers;

use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class SellCoinController extends BaseController
{
    private const ERRORS = [
        'ERROR_FIELD' => "error",
        'ERROR_MESSAGE' => "Error while selling coins",
        'COIN_NOT_FOUND' => "coin not found"
    ];

    private SellCoinService $sellCoinService;

    public function __construct(SellCoinService $sellCoinService)
    {
        $this->sellCoinService = $sellCoinService;
    }

    public function sellCoin(Request $request): JsonResponse
    {
        if(!$request->has("coinId"))
        {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['ERROR_MESSAGE']
            ], Response::HTTP_BAD_REQUEST);
        }
        try
        {
            $this->sellCoinService->execute(
                $request->get("coinId"),
                $request->get("walletId"),
                $request->get("amountUSD")
            );
            return response()->json([
                // 200 - Successful operation
            ], Response::HTTP_OK);
        }
        catch(Exception $exception)
        {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['ERROR_MESSAGE']
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
