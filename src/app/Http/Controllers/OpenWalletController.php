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
        'USER_NOT_FOUND_ERROR' => "user not found",
        'USER_ID_FIELD_NOT_FOUND_ERROR' => "user id field not found",
        'ERROR_FIELD' => "error",
        'ERROR_MESSAGE' => "Error while creating the wallet"
    ];

    private ServiceManager $serviceManager;

    public function __construct(ServiceManager $serviceManager){
        $this->serviceManager = $serviceManager;
    }

    public function openWallet(Request $request): JsonResponse
    {
        if ($request->has("userId") === false) {
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['ERROR_MESSAGE']
            ],Response::HTTP_BAD_REQUEST);
        }

        $response = $this->serviceManager->getResponse($request);
        if($response == self::ERRORS['USER_ID_FIELD_NOT_FOUND_ERROR']){
            return response()->json([
                self::ERRORS['ERROR_FIELD'] => self::ERRORS['ERROR_MESSAGE']
            ],Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'walletId' => $response
        ],Response::HTTP_OK);
    }

}
