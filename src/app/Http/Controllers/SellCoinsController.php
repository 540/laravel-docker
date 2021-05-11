<?php

namespace App\Http\Controllers;

use App\Http\Services\Adopter\BuyCoinsAdapterService;
use App\Http\Services\Adopter\SellCoinsAdapterService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SellCoinsController extends BaseController
{
    /**
     * @var SellCoinsAdapterService
     */
    private SellCoinsAdapterService $sellCoinsService;

    /**
     * SellCoinsController constructor.
     * @param SellCoinsAdapterService $sellCoinsService
     */
    public function __construct(SellCoinsAdapterService $sellCoinsService)
    {
        $this->sellCoinsService = $sellCoinsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $idCoin = $request->input("coin_id");
        $idWallet = $request->input("wallet_id");
        $coinsAmount = $request->input("amount_coins");

        $operation = "sell";
        try{
            $buyCoinsResponse = $this->sellCoinsService->execute($idCoin, $idWallet, $coinsAmount,  $operation);
        }catch (\Exception $ex){
            return response()->json([
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Devolver json
        return response()->json([
            'buy_response' => $buyCoinsResponse
        ], Response::HTTP_OK);
    }

}
