<?php

namespace App\Http\Controllers;

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
    private ServiceManager $serviceManager;

    public function __construct(ServiceManager $serviceManager){
        $this->serviceManager = $serviceManager;
    }

    public function openWallet(Request $request): JsonResponse
    {
        $response = $this->serviceManager->getResponse($request);
        if($response == "wrong"){
            return response()->json([
                'error' => "Error while creating the wallet"
            ],Response::HTTP_NOT_FOUND);
        }
        if($response == null){
            return response()->json([
                'error' => "Error while creating the wallet"
            ],Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'walletId' => $response
        ],Response::HTTP_OK);
    }

}
