<?php

namespace App\Http\Controllers;

use App\Errors\Errors;
use App\Responses\Responses;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class SellCoinController extends BaseController
{

    private SellCoinService $sellCoinService;

    public function __construct(SellCoinService $sellCoinService) {
        $this->sellCoinService = $sellCoinService;
    }

    public function sellCoin(Request $request): JsonResponse {

        if(!$request->has("coin_id")||!$request->has("wallet_id")||!$request->has("amount_usd")) {
            return response()->json([
                Response::HTTP_BAD_REQUEST => Errors::BAD_REQUEST_ERROR
            ], Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->sellCoinService->execute(
                $request->get("coin_id"),
                $request->get("wallet_id"),
                $request->get("amount_usd"));
            return response()->json([
                Response::HTTP_BAD_REQUEST => Responses::SUCCESS_MESSAGE
            ], Response::HTTP_OK);
        }
        catch (Exception $exception) {
            return response()->json([
                Response::HTTP_BAD_REQUEST => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
