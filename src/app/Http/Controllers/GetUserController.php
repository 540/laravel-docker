<?php

namespace App\Http\Controllers;

use App\Services\EarlyAdopter\IsEarlyAdopterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetUserController extends BaseController
{
    private IsEarlyAdopterService $isEarlyAdopterService;

    public function __constructor($isEarlyAdopterService)
    {
        $this->isEarlyAdopterService = $isEarlyAdopterService;
    }

    public function __invoke($email): JsonResponse
    {
        try{
            $isEarlyAdopter = $this->isEarlyAdopterService->execute($email);
        }catch (Exception $exception){
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }
        return response()->json([
            'earlyAdopter' => $isEarlyAdopter
        ], Response::HTTP_OK);
    }
}
