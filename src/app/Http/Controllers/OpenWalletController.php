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
        return response()->json([
            'error' => "Error while creating the wallet"
        ],Response::HTTP_BAD_REQUEST);
    }

}
