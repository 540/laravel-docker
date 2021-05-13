<?php

namespace App\Http\Controllers;

use App\Services\Coin\CoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use function PHPUnit\Framework\isNull;

class CoinController extends BaseController
{
    /**
     * @var coinService
     */
    private CoinService $coinService;

    /**
     * WalletController constructor.
     * @param CoinService $coinService
     */
    public function __construct(CoinService $coinService)
    {
        $this->coinService = $coinService;
    }

    public function buy(Request $request)
    {
        try {
            $coinId = $request->coin_id;
            $walletId = $request->wallet_id;
            $amountUsd = $request->amount_usd;
            if (($coinId && $walletId && $amountUsd) == null) {
                throw new Exception('Insufficient arguments in the POST');
            }

            $this->coinService->executeBuy($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            return $this->exceptionHandler($exception);
        }

        return response()->json(
            [
                'status' => 'Successful operation', 'message' => 'The buy has been successfully completed'
            ],
            Response::HTTP_OK
        );
    }

    public function sell(Request $request)
    {
        try {
            $coinId = $request->coin_id;
            $walletId = $request->wallet_id;
            $amountUsd = $request->amount_usd;
            if (($coinId && $walletId && $amountUsd) == null) {
                throw new Exception('Insufficient arguments in the POST');
            }

            $this->coinService->executeSell($coinId, $walletId, $amountUsd);
        } catch (Exception $exception) {
            return $this->exceptionHandler($exception);
        }

        return response()->json(
            [
                'status' => 'Successful operation', 'message' => 'The sell has been successfully completed'
            ],
            Response::HTTP_OK
        );
    }

    private function exceptionHandler($exception): JsonResponse
    {
        if ($exception->getMessage() === "Coin not found") {
            return response()->json(
                [
                    'status' => 'Coin with the specified ID was not found', 'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        if ($exception->getMessage() === "Wallet not found") {
            return response()->json(
                [
                    'status' => 'Wallet with the specified ID was not found', 'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json(
            [
                'status' => 'Bad Request Error', 'message' => $exception->getMessage()
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
