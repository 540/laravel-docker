<?php


namespace App\Http\Controllers;


use App\Services\EarlyAdopter\IsEarlyAdopterService;
use http\Env\Request;
use Illuminate\Http\Response;
use Tests\Integration\Controller\CoinBuyerControllerTest;

class coinBuyerController extends BaseController {

    private $coinBuyerService;

    /**
     * IsEarlyAdopterUserController constructor.
     */
    public function __construct(CoinBuyerService $coinBuyerService)
    {
        $this->coinBuyerService = $coinBuyerService;
    }

    public function buyCoin (Request $request) : \Illuminate\Http\JsonResponse
    {

        try {
            return $this->coinBuyerService->execute($request);

        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }


    }

}
