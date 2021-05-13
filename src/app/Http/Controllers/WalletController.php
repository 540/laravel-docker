<?php

namespace App\Http\Controllers;

use App\Services\Wallet\WalletService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class WalletController extends BaseController
{
    /**
     * @var walletService
     */
    private $walletService;

    /**
     * WalletController constructor.
     * @param WalletService $walletService
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * @param string $wallet_id
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $wallet = $this->walletService->execute($wallet_id);
        } catch (Exception $exception) {
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
        return response()->json($wallet, Response::HTTP_OK);
    }

    /**
     * @param string $wallet_id
     * @return JsonResponse
     * @throws Exception
     */
    public function balance(string $wallet_id): JsonResponse
    {
        try {
            $balanceUsd = $this->walletService->executeBalance($wallet_id);
        } catch (Exception $exception) {
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
        return response()->json(
            [
                'balance_usd' => $balanceUsd
            ],
            Response::HTTP_OK
        );
    }
}
