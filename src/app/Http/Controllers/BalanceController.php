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
    private BalanceAdopterService $balanceService;

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
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(string $idWallet):JsonResponse
    {
        try{
            $coins = $this->balanceService->execute($idWallet);
        }catch (\Exception $ex){
            return response()->json([
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $balance = 0;
        for($i = 0; $i < sizeof($coins); $i++) {
            $balance = $balance + $this->balanceService->obtainBalance($coins[$i]->id_coin, $idWallet);
        }

        return response()->json([
            'balance_usd' => $balance
        ], Response::HTTP_OK);
    }
}
