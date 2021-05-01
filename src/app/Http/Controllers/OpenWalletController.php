<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Scalar\String_;

class OpenWalletController extends BaseController
{

    public function __constructor(){
    }

    public function openWallet(String $userId): JsonResponse
    {
        return response()->json([
            'error' => "Error while creating the wallet"
        ],Response::HTTP_NOT_FOUND);
    }

}
