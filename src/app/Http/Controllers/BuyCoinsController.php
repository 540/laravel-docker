<?php
namespace App\Http\Controllers;

use App\Http\Services\Adopter\BuyCoinsAdapterService;
use App\Http\Services\Adopter\GetWalletCryptocurrenciesService;
use App\Http\Services\Adopter\OpenWalletService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class BuyCoinsController
 * @package App\Http\Controllers
 */
class BuyCoinsController extends BaseController
{
    /**
     * @var BuyCoinsAdapterService
     */
    private $buyCoinsService;

    /**
     * BuyCoinsController constructor.
     * @param BuyCoinsAdapterService $openWalletService
     */
    public function __construct(BuyCoinsAdapterService $openWalletService)
    {
        $this->buyCoinsService = $openWalletService;
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Recibir los datos en json de postman
        $idCoin = $request->input("coin_id");
        $idWallet = $request->input("wallet_id");
        $amount = $request->input("amount_usd");

        // Conectar a la API con la moneda requerida
        $coinData = json_decode($this->curl("https://api.coinlore.net/api/ticker/?id=".$idCoin));

        // Indicar operación
        $operation = "buy";
        // Obtener precio de la moneda
        $coinPrice = $coinData[0]->price_usd;
        // Caulcular los bitcoins comprados
        $buyedCoins = $amount/$coinPrice;

        // Insertar en la cartera
        try{
            $buyCoinsResponse = $this->buyCoinsService->execute($idCoin, $idWallet, $amount, $buyedCoins, $coinPrice, $operation);
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
