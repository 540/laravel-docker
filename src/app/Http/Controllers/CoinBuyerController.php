<?php


namespace App\Http\Controllers;


use App\Errors\Errors;
use App\Services\CoinBuy\CoinBuyerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;


class CoinBuyerController extends BaseController {

    private CoinBuyerService $coinBuyerService;

    public function __construct(CoinBuyerService $coinBuyerService)
    {
        $this->coinBuyerService = $coinBuyerService;
    }

    public function buyCoin (Request $request) : JsonResponse
    {
        if (($request->has('coin_id') === false) || ($request->has('wallet_id') === false) || ($request->has('amount_usd') === false)) {
            return response()->json([
                'error' => Errors::BAD_REQUEST_ERROR
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->coinBuyerService->execute($request->input('coin_id'),$request->input('wallet_id'),$request->input('amount_usd'));
            return response()->json([
                'bought' => 'successful operation'
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json([
                'error' => Errors::COIN_SPICIFIED_ID_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }


    }

}
