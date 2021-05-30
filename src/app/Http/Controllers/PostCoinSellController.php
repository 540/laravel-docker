<?php

namespace App\Http\Controllers;

use App\Services\Coin\PostCoinSellService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class PostCoinSellController extends BaseController
{
    /**
     * @var postCoinSellService
     */
    private PostCoinSellService $postCoinSellService;

    /**
     * PostCoinSellController constructor.
     * @param PostCoinSellService $postCoinSellService
     */
    public function __construct(PostCoinSellService $postCoinSellService)
    {
        $this->postCoinSellService = $postCoinSellService;
    }

    public function __invoke(Request $request)
    {
        try {
            $coinId = $request->coin_id;
            $walletId = $request->wallet_id;
            $amountUsd = $request->amount_usd;
            if (($coinId && $walletId && $amountUsd) == null && $amountUsd !== 0) {
                throw new Exception('Insufficient arguments in the POST');
            }
            if ($amountUsd < 0.01) {
                throw new Exception('Insufficient amount to sell');
            }

            $this->postCoinSellService->execute($coinId, $walletId, $amountUsd);
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
