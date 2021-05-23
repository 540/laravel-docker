<?php

namespace App\Http\Controllers;

use App\Errors\Errors;
use App\Services\OpenWallet\OpenWalletService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class OpenWalletController extends BaseController
{
    private OpenWalletService $openWalletService;

    public function __construct(OpenWalletService $serviceManager)
    {
        $this->openWalletService = $serviceManager;
    }

    public function openWallet(Request $request): JsonResponse
    {
        if ($request->has("user_id") === false)
        {
            return response()->json([
                Response::HTTP_BAD_REQUEST => Errors::BAD_REQUEST
            ],Response::HTTP_BAD_REQUEST);
        }
        try
        {
            $walletId = $this->openWalletService->execute($request->get("user_id"));
            return response()->json([
                'wallet_id' => $walletId
            ],Response::HTTP_OK);
        } catch (Exception $exception)
        {
            return response()->json([
                Response::HTTP_NOT_ACCEPTABLE => $exception->getMessage()
            ],Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
