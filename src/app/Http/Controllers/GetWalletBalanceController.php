<?php

namespace App\Http\Controllers;

use App\Services\Wallet\GetWalletBalanceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetWalletBalanceController extends BaseController
{
    /**
     * @var getWalletBalanceService
     */
    private $getWalletBalanceService;

    /**
     * GetWalletBalanceController constructor.
     * @param GetWalletBalanceService $getWalletBalanceService
     */
    public function __construct(GetWalletBalanceService $getWalletBalanceService)
    {
        $this->getWalletBalanceService = $getWalletBalanceService;
    }

    /**
     * @param string $wallet_id
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $balanceUsd = $this->getWalletBalanceService->execute($wallet_id);
        } catch (Exception $exception) {
            return $this->exceptionHandler($exception);
        }
        return response()->json(
            [
                'balance_usd' => $balanceUsd
            ],
            Response::HTTP_OK
        );
    }

    private function exceptionHandler($exception): JsonResponse
    {
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
