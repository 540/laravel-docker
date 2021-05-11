<?php


namespace App\Http\Controllers;

use App\Http\Services\Adopter\BalanceAdopterService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BalanceController extends BaseController
{
    /**
     * @var BalanceAdopterService
     */
    private $balanceService;

    /**
     * BalanceAdopterService constructor.
     * @param BalanceAdopterService $balanceService
     */
    public function __construct(BalanceAdopterService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    /**
     * @param string $idWallet
     * @return string
     * @throws \Exception
     */
    public function __invoke(string $idWallet):JsonResponse
    {
        // Comprobar cuántos tipos de monedas hay en esa cartera
        $coins = $this->balanceService->execute($idWallet);

        // Conectarse a la API para cada tipo de moneda
        $balance = 0;
        for($i = 0; $i < sizeof($coins); $i++) {
            $coinData = json_decode($this->curl("https://api.coinlore.net/api/ticker/?id=" . $coins[$i]->id_coin));
            $coinPrice = $coinData[0]->price_usd;
            $possesedCoins = $this->balanceService->obtainBalance($coins[$i]->id_coin, $idWallet);
            $balance = $balance + $possesedCoins * $coinPrice;
        }

        return response()->json([
            'balance_usd' => $balance
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
