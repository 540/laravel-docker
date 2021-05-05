<?php

namespace App\Http\Controllers;

use App\Http\Services\Adopter\GetWalletCryptocurrenciesService;
use App\Http\Services\Adopter\OpenWalletService;
use App\Infrastructure\Database\WalletDataSource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class OpenWalletController
 * @package App\Http\Controllers
 */
class OpenWalletController extends BaseController
{
    /**
     * @var OpenWalletService
     */
    private $openWalletService;

    /**
     * IsEarlyAdopterController constructor.
     * @param OpenWalletService $walletService
     */
    public function __construct(OpenWalletService $walletService)
    {
        $this->openWalletService = $walletService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        try{
            $user_id = $request->input("user_id");
            $walletId = $this->openWalletService->execute($user_id);
        }catch (\Exception $ex){
            return response()->json([
                'error' => $ex->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Devolver json
        return response()->json([
            'wallet_id' => $walletId
        ], Response::HTTP_OK);
    }
}
