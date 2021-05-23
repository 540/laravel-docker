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
    private const RESPONSE_MSG = [
        'SUCCESS_MESSAGE' => "Successful operation",
        'BAD_REQUEST_MESSAGE' => "Bad request error",
        'COIN_NOT_FOUND_MESSAGE' => "A coin with specified ID was not found"
    ];

    private SellCoinService $sellCoinService;

    public function __construct(SellCoinService $sellCoinService) {
        $this->sellCoinService = $sellCoinService;
    }

    public function sellCoin(Request $request): JsonResponse {

        if(!$request->has("coin_id")||!$request->has("wallet_id")||!$request->has("amount_usd")) {
            return response()->json([
                Response::HTTP_BAD_REQUEST => self::RESPONSE_MSG['BAD_REQUEST_MESSAGE']
            ], Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->sellCoinService->execute(
                $request->get("coin_id"),
                $request->get("wallet_id"),
                $request->get("amount_usd"));
            return response()->json([
                Response::HTTP_OK => self::RESPONSE_MSG['SUCCESS_MESSAGE']
            ], Response::HTTP_OK);
        }
        catch (Exception $exception) {
            return response()->json([
                Response::HTTP_NOT_FOUND => self::RESPONSE_MSG['COIN_NOT_FOUND_MESSAGE']
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
