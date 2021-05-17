<?php

namespace App\Http\Controllers;

use App\Services\Coin\PostCoinBuyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class PostCoinBuyController extends BaseController
{
    /**
     * @var postCoinBuyService
     */
    private PostCoinBuyService $postCoinBuyService;

    /**
     * PostCoinBuyController constructor.
     * @param PostCoinBuyService $postCoinBuyService
     */
    public function __construct(PostCoinBuyService $postCoinBuyService)
    {
        $this->postCoinBuyService = $postCoinBuyService;
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
                throw new Exception('Insufficient amount to buy');
            }

            $this->postCoinBuyService->execute($coinId, $walletId, $amountUsd);
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
