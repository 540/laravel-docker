<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class GetUserController extends BaseController
{
    private $isEarlyAdopterService;

    public function __constructor($isEarlyAdopterService){
        $this->isEarlyAdopterService = $isEarlyAdopterService;
    }

    public function __invoke($id): JsonResponse
    {
        try{
            $isEarlyAdopter = $this->isEarlyAdopterService->execute();
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
