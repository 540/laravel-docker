<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class OpenWalletController extends BaseController
{
    public function store(Request $request){
        //$wallet = DB::table('wallet')->create('idWallet', $request->data()['userId'])->;
    }
}
