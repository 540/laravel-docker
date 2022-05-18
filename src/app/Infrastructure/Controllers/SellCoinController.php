<?php

namespace App\Infrastructure\Controllers;

use App\Application\CoinService\BuyCoinService;
use Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
class SellCoinController extends BaseController
{
    private BuyCoinService $BuyCoinService;

    /**
     * BuyCoinController constructor.
     */
    public function __construct(BuyCoinService $CoinService)
    {
        $this->BuyCoinService = $CoinService;
    }

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
        try {
            $BuyCoinService = $this->BuyCoinService->SellCoin($request->coin_id,$request->wallet_id,$request->amount_usd);
        } catch (Exception $exception) {
            if ($exception->getMessage() == "A coin with the specified ID was not found.") {
                return response()->json([
                    'error' => $exception->getMessage()
                ], Response::HTTP_NOT_FOUND);
            }else{
                return response()->json([
                    'error' => $exception->getMessage()
                ], Response::HTTP_SERVICE_UNAVAILABLE);
            }
        }


        return response()->json([
            $BuyCoinService
        ], Response::HTTP_OK);
    }
}

