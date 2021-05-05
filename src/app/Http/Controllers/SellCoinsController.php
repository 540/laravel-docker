<?php

namespace App\Http\Controllers;

use App\Http\Services\Adopter\BuyCoinsAdapterService;
use App\Http\Services\Adopter\SellCoinsAdapterService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SellCoinsController extends BaseController
{
    /**
     * @var SellCoinsAdapterService
     */
    private $sellCoinsService;

    /**
     * SellCoinsController constructor.
     * @param SellCoinsAdapterService $buyCoinsService
     */
    public function __construct(SellCoinsAdapterService $buyCoinsService)
    {
        $this->sellCoinsService = $buyCoinsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $idCoin = $request->input("coin_id");
        $idWallet = $request->input("wallet_id");
        $coinsAmount = $request->input("amount_coins");

        $coinData = json_decode($this->curl("https://api.coinlore.net/api/ticker/?id=".$idCoin));

        $operation = "sell";
        $coinPrice = $coinData[0]->price_usd;
        $usdSellPrice = $coinsAmount*$coinPrice;

        try{
            $buyCoinsResponse = $this->sellCoinsService->execute($idCoin, $idWallet, $coinsAmount, $usdSellPrice, $coinPrice, $operation);
        }catch (\Exception $ex){
            return response()->json([
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Devolver json
        return response()->json([
            'buy_response' => $buyCoinsResponse
        ], Response::HTTP_OK);
    }

    /**
     * @param $url
     * @return bool|string
     */
    private function curl($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_VERBOSE => false,
            CURLOPT_USERAGENT => 'Coinlore PHP/API',
            CURLOPT_POST => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 65
        ));
        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;
    }
}
