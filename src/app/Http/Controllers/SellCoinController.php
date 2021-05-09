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
        'ERROR_MESSAGE' => "Error while selling coins"
    ];

    private SellCoinService $sellCoinService;

    public function __construct(SellCoinService $sellCoinService)
    {
        $this->sellCoinService = $sellCoinService;
    }

    public function sellCoin(Request $request): JsonResponse
    {
        if(!$request->has("userId"))
        {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['ERROR_MESSAGE']
            ],Response::HTTP_BAD_REQUEST);
        }
        try
        {
            $this->sellCoinService->execute(
                $request->get("coin_id"),
                $request->get("wallet_id"),
                $request->get("amount_usd")
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
