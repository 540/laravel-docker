<?php


namespace App\Http\Controllers;

use App\Http\Services\Adopter\GetWalletCryptocurrenciesService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetWalletCryptocurrenciesController extends BaseController
{
    /**
     * @var GetWalletCryptocurrenciesService
     */
    private $walletService;

    /**
     * IsEarlyAdopterController constructor.
     * @param GetWalletCryptocurrenciesService $walletService
     */
    public function __construct(GetWalletCryptocurrenciesService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * @param string $wallet_id
     * @param $walletService
     * @return JsonResponse
     */
    public function __invoke(string $wallet_id): JsonResponse
    {
        try{
            $wallet = $this->walletService->execute($wallet_id);
        }catch (Exception $ex){
            return response()->json([
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'wallet' => $wallet
        ], Response::HTTP_OK);
    }
}
