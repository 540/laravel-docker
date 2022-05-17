<?php

namespace App\Infrastructure\Controllers;

use App\Application\EarlyAdopter\CoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetCoinController extends BaseController
{
    private $CoinService;

    /**
     * UserController constructor.
     */
    public function __construct(CoinService $CoinService)
    {
        $this->CoinService = $CoinService;
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $CoinService = $this->CoinService->execute($id);
        } catch (Exception $exception) {
            return response()->json([
                'error' => "A coin with the specified ID was not found."
            ], 404);
        }

        return response()->json([
            $CoinService
        ], Response::HTTP_OK);
    }
}
