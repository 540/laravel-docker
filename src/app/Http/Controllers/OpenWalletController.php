<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Scalar\String_;

class OpenWalletController extends BaseController
{

    public function __constructor(){
    }

    public function openWallet(Request $request): JsonResponse
    {
        if($request->get("userId") == "wrong"){
            return response()->json([
                'error' => "Error while creating the wallet"
            ],Response::HTTP_NOT_FOUND);
        }
        if($request->get("userId") == null){
            return response()->json([
                'error' => "Error while creating the wallet"
            ],Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'walletId' => "walletTest"
        ],Response::HTTP_OK);
    }

}
