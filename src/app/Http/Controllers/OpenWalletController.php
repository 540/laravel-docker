<?php

namespace App\Http\Controllers;

use App\Services\OpenWalletService\OpenWalletService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class OpenWalletController extends BaseController
{
    private const ERRORS = [
        'USER_NOT_FOUND_ERROR' => "user not found",
        'USER_ID_FIELD_NOT_FOUND_ERROR' => "user id field not found",
        'ERROR_FIELD' => "error",
        'ERROR_MESSAGE' => "Error while creating the wallet"
    ];

    private OpenWalletService $openWalletService;

    public function __construct(OpenWalletService $serviceManager){
        $this->openWalletService = $serviceManager;
    }

    public function openWallet(Request $request): JsonResponse
    {
        if($request->has("userId") === false)
        {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['ERROR_MESSAGE']
            ], Response::HTTP_BAD_REQUEST);
        }
        try
        {
            $walletId = $this->openWalletService->execute($request->get("userId"));
            return response()->json([
                'walletId' => $walletId
            ], Response::HTTP_OK);
        }
        catch(Exception $exception)
        {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['ERROR_MESSAGE']
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
