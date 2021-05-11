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
    private BuyCoinsAdapterService $buyCoinsService;

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
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Recibir los datos en json de postman
        $idCoin = $request->input("coin_id");
        $idWallet = $request->input("wallet_id");
        $amount = $request->input("amount_usd");

        // Indicar operación
        $operation = "buy";

        // Insertar en la cartera
        try{
            $buyCoinsResponse = $this->buyCoinsService->execute($idCoin, $idWallet, $amount, $operation);
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
}
