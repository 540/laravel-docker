<?php


namespace App\Http\Controllers;


use App\Services\CoinBuy\CoinBuyerService;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Services\WalletBalance\GetWalletBalanceService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;


class coinBuyerController extends BaseController {

    private CoinBuyerService $coinBuyerService;

    /**
     * IsEarlyAdopterUserController constructor.
     */
    public function __construct(CoinBuyerService $coinBuyerService)
    {
        $this->coinBuyerService = $coinBuyerService;
    }


    public function buyCoin ($request) : JsonResponse
    {
        if (($request->has('coin_id') === false) || ($request->has('wallet_id') === false) || ($request->has('amount_usd') === false)) {
            return response()->json([
                'error' => 'wrong field'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->coinBuyerService->execute($request->input('coin_id'),$request->input('wallet_id'),$request->input('amount_id'));
            return response()->json([
                'bought' => 'success operation'
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }


    }

}
