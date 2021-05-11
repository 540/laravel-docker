<?php

namespace App\Http\Controllers;

use App\Services\OpenWalletService\OpenWalletService;
use App\Services\ServiceManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Scalar\String_;

class OpenWalletController extends BaseController
{
    private const ERRORS = [
        'USER_NOT_FOUND_ERROR' => "A user with the specified ID was not found.",
        'BAD_REQUEST_ERROR' => "bad request error",
        'ERROR_FIELD' => "error",
        'ERROR_MESSAGE' => "Error while creating the wallet"
    ];

    private OpenWalletService $openWalletService;

    public function __construct(OpenWalletService $serviceManager){
        $this->openWalletService = $serviceManager;
    }

    public function openWallet(Request $request): JsonResponse
    {
        if ($request->has("user_id") === false) {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['BAD_REQUEST_ERROR']
            ],Response::HTTP_BAD_REQUEST);
        }
        try {
            $walletId = $this->openWalletService->execute($request->get("user_id"));
            return response()->json([
                'wallet_id' => $walletId
            ],Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['USER_NOT_FOUND_ERROR']
            ],Response::HTTP_NOT_FOUND);
        }
    }

}
